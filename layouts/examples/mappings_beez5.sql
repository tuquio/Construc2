#
# remap positions from
#   Beez5        to  Construc2
# -------------------------------------
#   debug        =   debug
#   position-0   =   header-above-1
#   position-1   =   nav
#   position-2   =   breadcrumbs
#   position-3   =   column-4
#   position-4   =   column-4
#   position-5   =   column-2
#   position-6   =   column-3
#   position-7   =   column-1
#   position-8   =   column-3
#   position-9   =   footer-above-1
#   position-10  =   footer-above-2
#   position-11  =   footer-above-3
#   position-12  =   content-above-1
#   position-13  =   content-below-1
#   position-14  =   footer
#   position-15  =   nav-below-1
#

UPDATE `#__modules` SET `position`="debug" 				WHERE `position`='debug';
UPDATE `#__modules` SET `position`="header-above-1"		WHERE `position`='position-0';
UPDATE `#__modules` SET `position`="nav"				WHERE `position`='position-1';
UPDATE `#__modules` SET `position`="breadcrumbs"		WHERE `position`='position-2';
UPDATE `#__modules` SET `position`="column-4" 			WHERE `position`='position-3';
UPDATE `#__modules` SET `position`="column-4" 			WHERE `position`='position-4';
UPDATE `#__modules` SET `position`="column-2" 			WHERE `position`='position-5';
UPDATE `#__modules` SET `position`="column-3" 			WHERE `position`='position-6';
UPDATE `#__modules` SET `position`="column-1" 			WHERE `position`='position-7';
UPDATE `#__modules` SET `position`="column-3" 			WHERE `position`='position-8';
UPDATE `#__modules` SET `position`="footer-above-1"		WHERE `position`='position-9';
UPDATE `#__modules` SET `position`="footer-above-2"		WHERE `position`='position-10';
UPDATE `#__modules` SET `position`="footer-above-3"		WHERE `position`='position-11';
UPDATE `#__modules` SET `position`="content-above-1"	WHERE `position`='position-12';
UPDATE `#__modules` SET `position`="content-below-1"	WHERE `position`='position-13';
UPDATE `#__modules` SET `position`="footer"				WHERE `position`='position-14';
UPDATE `#__modules` SET `position`="nav-below-1"		WHERE `position`='position-15';
