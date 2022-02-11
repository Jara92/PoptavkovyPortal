<?php

namespace App\Tests\Unit\Helper;

use App\Helper\UrlHelper;
use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase
{
    public function testCreateAlias(): void
    {
        $title = ["Toto je Titulek.", "ěščřžýáíé", "Lomítka/jsou/super!", "12345", "--111ahoj???ahoj--", "&ahoj=1", "<script>aa</script>"];
        $alias = ["toto-je-titulek", "escrzyaie", "lomitka-jsou-super", "12345", "111ahoj-ahoj", "ahoj-1", "script-aa-script"];

        for ($i = 0; $i < count($title); $i++) {
            $this->assertSame($alias[$i], UrlHelper::createAlias($title[$i]));
        }
    }

    public function testCreateAliasEdgeCases(): void
    {
        $title = [""];
        $alias = ["n-a"];

        for ($i = 0; $i < count($title); $i++) {
            $this->assertSame($alias[$i], UrlHelper::createAlias($title[$i]));
        }
    }

    public function testCreateIdAlias(): void
    {
        $id = [1, -1];
        $title = ["Titulek moji stranky", "Test"];
        $alias = ["1-titulek-moji-stranky", "1-test"];

        for ($i = 0; $i < count($title); $i++) {
            $this->assertSame($alias[$i], UrlHelper::createIdAlias($id[$i], $title[$i]));
        }
    }
}