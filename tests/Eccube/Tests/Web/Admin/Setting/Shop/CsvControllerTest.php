<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


namespace Eccube\Tests\Web\Admin\Setting\Shop;

use Eccube\Common\Constant;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

class CsvControllerTest extends AbstractAdminWebTestCase
{

    public function testRoutingCsv()
    {
        $this->client->request('GET', $this->app['url_generator']->generate('admin_setting_shop_csv', array('id' => 1)));
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testGetCsv()
    {

        $CsvType = $this->app['eccube.repository.master.csv_type']->find(1);
        $this->assertNotEmpty($CsvType);

        $Csv = $this->app['eccube.repository.csv']->findBy(array('CsvType' => $CsvType, 'enable_flg' => Constant::ENABLED), array('rank' => 'ASC'));
        $this->assertNotEmpty($Csv);
    }


    public function testSetCsv()
    {
        $this->app['orm.em']->getConnection()->beginTransaction();

        $Csv = $this->app['eccube.repository.csv']->find(1);
        $Csv->setRank(1);
        $Csv->setEnableFlg(Constant::DISABLED);

        $this->app['orm.em']->flush();

        $Csv2 = $this->app['eccube.repository.csv']->find(1);
        $this->assertEquals(Constant::DISABLED, $Csv2->getEnableFlg());

        $this->app['orm.em']->getConnection()->rollback();
    }

}
