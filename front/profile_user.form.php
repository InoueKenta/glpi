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

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

$profile = new Profile();
$right = new Profile_User();
$user = new User();

if (isset($_POST["add"])) {
   if (!isset($_POST['profiles_id']) || $_POST['profiles_id'] <= 0
       || !isset($_POST['entities_id']) || $_POST['entities_id'] < 0
       || !isset($_POST['users_id']) || $_POST['users_id'] < 0) {

      addMessageAfterRedirect($LANG['common'][24],false,ERROR);
      glpi_header($_SERVER['HTTP_REFERER']);
   }
   // TODO right check on User not on Profile_User
   if ($user->can($_POST['users_id'],'r')
       && Profile::currentUserHaveMoreRightThan(array($_POST['profiles_id'] => $_POST['profiles_id']))
       && haveAccessToEntity($_POST['entities_id'])) {

      if ($right->add($_POST)) {
         Event::log($_POST["users_id"], "users", 4, "setup",
                    $_SESSION["glpiname"]." ".$LANG['log'][61]);
      }
      glpi_header($_SERVER['HTTP_REFERER']);
   } else {
      displayRightError();
   }

} else if (isset($_POST["delete"])) {
   // TODO right check on User not on Profile_User
   checkRight("user","w");

   if (isset($_POST["item"]) && count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $right->delete(array('id' => $key));
         }
      }
      if (isset($_POST["entities_id"])) {
         // From entity tab
         Event::log($_POST["entities_id"], "entity", 4, "setup",
                    $_SESSION["glpiname"]." ".$LANG['log'][62]);
      } else if (isset($_POST["users_id"])) {
         Event::log($_POST["users_id"], "users", 4, "setup",
                    $_SESSION["glpiname"]." ".$LANG['log'][62]);
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["moveentity"])) {
   checkRight("user","w");
   if (isset($_POST['entities_id']) && $_POST['entities_id'] >= 0) {
      foreach ($_POST["item"] as $key => $val) {
         if ($val == 1) {
            $right->update(array('id'          => $key,
                                 'entities_id' => $_POST['entities_id']));
         }
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);

}
displayErrorAndDie("lost");

?>
