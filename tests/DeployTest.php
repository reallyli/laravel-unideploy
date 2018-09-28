<?php
/**
 * Created by PhpStorm.
 * User: reallyli
 * Date: 18/9/28
 * Time: 上午10:49
 */
 
namespace Reallyli\LaravelDeployer\Tests;

class DeployTest extends TestCase
{
    /**
     * Method description:testCommand
     *
     * @author reallyli <zlisreallyli@outlook.com>
     * @since 18/9/28
     * @param
     * @return mixed
     * 返回值类型：string，array，object，mixed（多种，不确定的），void（无返回值）
     */
    public function testCommand()
    {
        $this->assertSame(0, $this->artisan('deploy:list'));
        $this->assertSame(0, $this->artisan('deploy:configs'));
    }
}
