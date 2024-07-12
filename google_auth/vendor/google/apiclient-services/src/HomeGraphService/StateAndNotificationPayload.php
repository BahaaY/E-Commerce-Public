<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\HomeGraphService;

class StateAndNotificationPayload extends \Google\Model
{
  protected $devicesType = ReportStateAndNotificationDevice::class;
  protected $devicesDataType = '';
  public $devices;

  /**
   * @param ReportStateAndNotificationDevice
   */
  public function setDevices(ReportStateAndNotificationDevice $devices)
  {
    $this->devices = $devices;
  }
  /**
   * @return ReportStateAndNotificationDevice
   */
  public function getDevices()
  {
    return $this->devices;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StateAndNotificationPayload::class, 'Google_Service_HomeGraphService_StateAndNotificationPayload');
