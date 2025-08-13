<?php
/**
 * Keydera
 *
 * Keydera is a full-fledged licenser and updates manager.
 *
 * @package Keydera
 * @author CodeMonks
 * @see https://keydera.app
 * @link https://codecanyon.net/item/keydera-php-license-and-updates-manager/22351237
 * @license https://codecanyon.net/licenses/standard (Regular or Extended License)
 * @copyright Copyright (c) 2023, CodeMonks. (https://www.keydera.app)
 * @version 1.6.4
 */

defined('BASEPATH') or exit('No direct script access allowed');

/* External-API license activation extra response field value.
 * Usage:
 *  {[license_type]} for returning the license type
 *  {[client_email]} for returning client's email address
 *  {[license_expiry]} for returning license expiry date
 *  {[support_expiry]} for returning support expiry date
 *  {[updates_expiry]} for returning updates expiry date
 */
$config['extra_field_response'] = null;

/* Force HTTPS Connection
 * Usage:
 *     TRUE to force HTTPS connection
 *  FALSE to use (default) connection
 */
$config['force_ssl'] = false;

