<?php

/*
  Concerto Platform - Online Adaptive Testing Platform
  Copyright (C) 2011-2012, The Psychometrics Centre, Cambridge University

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; version 2
  of the License, and not any of the later versions.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

class TableIndex extends OTable {

    public $Table_id = 0;
    public $type = "";
    public static $mysql_table_name = "TableIndex";

    public function get_Table() {
        return Table::from_mysql_id($this->Table_id);
    }

    public function get_TableIndexColumns() {
        return TableIndexColumn::from_property(array("TableIndex_id" => $this->id));
    }

    public function mysql_delete() {
        parent::mysql_delete();
        $this->delete_object_links(TableIndexColumn::get_mysql_table());
    }

    public function to_XML() {
        $xml = new DOMDocument('1.0', "UTF-8");

        $element = $xml->createElement("TableIndex");
        $xml->appendChild($element);

        $id = $xml->createElement("id", htmlspecialchars($this->id, ENT_QUOTES, "UTF-8"));
        $element->appendChild($id);

        $table_id = $xml->createElement("Table_id", htmlspecialchars($this->Table_id, ENT_QUOTES, "UTF-8"));
        $element->appendChild($table_id);

        $type = $xml->createElement("type", htmlspecialchars($this->type, ENT_QUOTES, "UTF-8"));
        $element->appendChild($type);

        $tic = $xml->createElement("TableIndexColumns");
        $element->appendChild($tic);

        $columns = $this->get_TableIndexColumns();
        foreach ($columns as $col) {
            $elem = $col->to_XML();
            $elem = $xml->importNode($elem, true);
            $tic->appendChild($elem);
        }

        return $element;
    }

    public static function create_db($delete = false) {
        if ($delete) {
            if (!mysql_query("DROP TABLE IF EXISTS `TableIndex`;"))
                return false;
        }
        $sql = "
            CREATE TABLE IF NOT EXISTS `TableIndex` (
            `id` bigint(20) NOT NULL auto_increment,
            `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
            `created` timestamp NOT NULL default '0000-00-00 00:00:00',
            `Table_id` bigint(20) NOT NULL,
            `type` text NOT NULL,
            PRIMARY KEY  (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
            ";
        return mysql_query($sql);
    }

}

?>