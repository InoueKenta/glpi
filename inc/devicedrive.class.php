<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Remi Collet
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

/// Class DeviceDrive
class DeviceDrive extends CommonDevice {

   static function getTypeName() {
      global $LANG;

      return $LANG['devices'][19];
   }

   function getAdditionalFields() {
      global $LANG;


      return array_merge(parent::getAdditionalFields(),
                         array(array('name'  => 'is_writer',
                                     'label' => $LANG['device_drive'][0],
                                     'type'  => 'bool'),
                               array('name'  => 'speed',
                                     'label' => $LANG['device_drive'][1],
                                     'type'  => 'text'),
                               array('name'  => 'interfacetypes_id',
                                     'label' => $LANG['common'][65],
                                     'type'  => 'dropdownValue')));
   }

   function getSearchOptions() {
      global $LANG;

      $tab = parent::getSearchOptions();

      $tab[12]['table']         = $this->getTable();
      $tab[12]['field']         = 'is_writer';
      $tab[12]['linkfield']     = 'is_writer';
      $tab[12]['name']          = $LANG['device_drive'][0];
      $tab[12]['datatype']      = 'bool';

      $tab[13]['table']         = $this->getTable();
      $tab[13]['field']         = 'speed';
      $tab[13]['linkfield']     = 'speed';
      $tab[13]['name']          = $LANG['device_drive'][1];
      $tab[13]['datatype']      = 'text';

      $tab[14]['table']         = 'glpi_interfacetypes';
      $tab[14]['field']         = 'name';
      $tab[14]['linkfield']     = 'interfacetypes_id';
      $tab[14]['name']          = $LANG['common'][65];

      return $tab;
   }

   /**
    * return the display data for a specific device
    *
    * @return array
    */
   function getFormData() {
      global $LANG;

      $data['label'] = $data['value'] = array();
      if ($this->fields["is_writer"]) {
         $data['label'][] = $LANG['device_drive'][0];
         $data['value'][] = Dropdown::getYesNo($this->fields["is_writer"]);
      }
      if (!empty($this->fields["speed"])) {
         $data['label'][] = $LANG['device_drive'][1];
         $data['value'][] = $this->fields["speed"];
      }
      if ($this->fields["interfacetypes_id"]) {
         $data['label'][] = $LANG['common'][65];
         $data['value'][] = Dropdown::getDropdownName("glpi_interfacetypes",
                                                      $this->fields["interfacetypes_id"]);
      }

      return $data;
   }
}

?>