<?php

namespace App\Tests\Unit\Helper;

use App\Helper\UrlHelper;
use PHPUnit\Framework\TestCase;

class UrlHelperTest extends TestCase
{
    public function testCreateAlias(): void
    {
        $this->assertSame("toto-je-titulek", UrlHelper::createAlias("Toto je Titulek."));
        $this->assertSame("escrzyaie", UrlHelper::createAlias("ěščřžýáíé"));
        $this->assertSame("lomitka-jsou-super", UrlHelper::createAlias("Lomítka/jsou/super!"));
        $this->assertSame("12345", UrlHelper::createAlias("12345"));
        $this->assertSame("111ahoj-ahoj", UrlHelper::createAlias("--111ahoj???ahoj--"));
        $this->assertSame("ahoj-1", UrlHelper::createAlias("&ahoj=1"));
        $this->assertSame("script-aa-script", UrlHelper::createAlias("<script>aa</script>"));
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
        $this->assertSame("1-titulek-moji-stranky", UrlHelper::createIdAlias(1, "Titulek moji stranky"));
        $this->assertSame("1-test", UrlHelper::createIdAlias(-1, "Test"));
    }
}