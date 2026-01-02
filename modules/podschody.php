<?php

class HostinecPodSchody extends LunchMenuSource
{
    public $title = 'Hostinec Pod Schody';
    public $link = 'https://hostinecpodschody.cz';
    public $sourceLink = 'https://hostinecpodschody.cz/%f0%9f%8d%bd%ef%b8%8f-obedove-menu-hostinec-pod-schody/';
    public $icon = 'podschody';

 public function getTodaysMenu($todayDate, $cacheSourceExpires)
{
    $debug = false;

 /* Code for testing with local file  
 
 $rawHtml = file_get_contents('/obedy/hostinecpodschody.html');
    if ($rawHtml === false) {
        throw new RuntimeException('Failed to read local HTML file');
    }

    $rawHtml = preg_replace('/^\xEF\xBB\xBF/', '', $rawHtml);

    $html = str_get_html($rawHtml);
    if (!$html) {
        throw new RuntimeException('HTML parsing failed');
    }
*/

    $cached = $this->downloadHtml($cacheSourceExpires);
    $html   = $cached['html'];


    $result = new LunchMenuResult(time());

    /* ---------- Weekday definitions ---------- */

    $weekdays = [
        1 => 'PONDĚLÍ',
        2 => 'ÚTERÝ',
        3 => 'STŘEDA',
        4 => 'ČTVRTEK',
        5 => 'PÁTEK',
    ];

    $todayIndex = (int) date('w', $todayDate);
    $todayCz    = $weekdays[$todayIndex] ?? null;

    /* ---------- DOM scope ---------- */

    $nodes = $html->find('.entry-content h2, .entry-content h3, .entry-content li');

    /* ---------- Parser state ---------- */

    $mode = null;              // null | weekly | daily
    $collectDaily = false;

    foreach ($nodes as $node) {

        $text = trim(html_entity_decode($node->plaintext));
        if ($text === '') {
            continue;
        }

        /* ---------- Weekly menu header ---------- */
        if (preg_match('/^TÝDENNÍ NABÍDKA/u', $text)) {
            if ($debug) echo "\n[WEEKLY HEADER]\n";
            $mode = 'weekly';
            $collectDaily = false;
            continue;
        }

        /* ---------- Day header ---------- */
        if (preg_match('/^(PONDĚLÍ|ÚTERÝ|STŘEDA|ČTVRTEK|PÁTEK)/u', $text, $m)) {
            if ($debug) echo "\n[DAY HEADER] {$m[1]}\n";
            $mode = 'daily';
            $collectDaily = ($m[1] === $todayCz);
            continue;
        }

        /* ---------- Weekly menu items ---------- */
        if ($mode === 'weekly') {
            if ($this->parseDishLine($text, $dish, $price)) {
                if ($debug) echo "[WEEKLY] {$dish} | {$price}\n";
                $result->dishes[] = new Dish(
                    $dish,
                    $price,
                    null,
                    'Týdenní menu'
                );
            }
            continue;
        }

        /* ---------- Daily menu items (today only) ---------- */
        if ($mode === 'daily' && $collectDaily) {
            if ($this->parseDishLine($text, $dish, $price)) {
                if ($debug) echo "[DAILY] {$dish} | {$price}\n";
                $result->dishes[] = new Dish($dish, $price);
            }
        }
    }

    return $result;
}


    /* ---------- Shared line parser ---------- */

    private function parseDishLine(string $text, &$dish, &$price): bool
    {
        // Examples:
        // "Hovězí vývar – 49,-"
        // "Svíčková na smetaně 139 Kč"
        // "Burger týdne – 169,-"

        if (!preg_match('/^(.*?)[\s–\-]+([0-9]{2,3})\s*(?:Kč|,-)?/u', $text, $m)) {
            return false;
        }

        $dish  = trim($m[1]);
        $price = $m[2] . ' Kč';

        return true;
    }
}
