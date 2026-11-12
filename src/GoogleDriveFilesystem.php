<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\file-system
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\file\system;

use creocoder\flysystem\Filesystem;
use Google_Client;
use Google_Service_Drive;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\AdapterInterface;
use yii\base\InvalidConfigException;

/**
 * GoogleDriveFilesystem class
 *
 * */
class GoogleDriveFilesystem extends Filesystem {

    public $clientId;
    public $clientSecret;
    public $refreshToken;
    public $rootFolderId;
    public $driveId;

    /**
     * @inheritdoc
     */
    public function init() {
        if ($this->clientId === null) {
            throw new InvalidConfigException('The "clientId" property must be set.');
        }

        if ($this->clientSecret === null) {
            throw new InvalidConfigException('The "clientSecret" property must be set.');
        }

        if ($this->refreshToken === null) {
            throw new InvalidConfigException('The "refreshToken" property must be set.');
        }

        parent::init();
    }

    /**
     * @return AdapterInterface
     */
    protected function prepareAdapter() {
        $client = new Google_Client();
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->refreshToken($this->refreshToken);
        $service = new Google_Service_Drive($client);

        $options = [];
        if (isset($this->driveId)) {
            $options['teamDriveId'] = $this->driveId;
        }

        return new GoogleDriveAdapter($service, $this->rootFolderId, $options);
    }

}
