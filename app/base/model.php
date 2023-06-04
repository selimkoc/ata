<?php

namespace ATA;

class Model extends Core
{
  // Properties
  public $table;
  public $db;

  protected function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
  }

  public function get($id, $table = null)
  {
    $this->set_table($table);

    $query = "SELECT * FROM " . $this->table . " WHERE id=%d";
    $query = $this->db->prepare($query, [$id], '%d');
    return $this->db->get_row($query);
  }

  public function get_by($field, $value, $format = '%s', $table = null)
  {
    $this->set_table($table);

    $query = "SELECT * FROM " . $this->table . " WHERE %s=" . $format;
    $query = $this->db->prepare($query, [$field, $value], ['%s', $format]);
    return $this->db->get_row($query);
  }
  public function get_all_by($field, $value, $format = '%s',  $table = null)
  {

    $this->set_table($table);

    $query = "SELECT * FROM " . $this->table . " WHERE %s=" . $format;
    $query = $this->db->prepare($query, [$field, $value], ['%s', $format]);
    return $this->db->get_results($query);
  }

  public function insert_multiple($fields, $values, $formats, $table = null)
  {

    $this->set_table($table);

    $query = "INSERT INTO " . $this->table . " (" . implode(',', $fields) . ")";

    foreach ($values as $j => $value)
      $values_string[$j] =  $this->db->prepare('(' . implode(',', $formats) . ')', $value);

    $query .= ' VALUES ' . implode(',', $values_string);

    return $this->db->query($query);
  }

  public function update_multiple($data, $where, $format, $where_format, $table = null)
  {

    $this->set_table($table);

    $i          = 0;
    $q          = "UPDATE " . $this->table . " SET ";
    $format     = array_values((array) $format);
    $escaped    = array();

    foreach ((array) $data as $key => $value) {
      $f         = isset($format[$i]) && in_array($format[$i], array('%s', '%d'), TRUE) ? $format[$i] : '%s';
      $escaped[] = esc_sql($key) . " = " . $this->db->prepare($f, $value);
      $i++;
    }

    $q         .= implode(', ', $escaped);
    $where      = (array) $where;
    $where_keys = array_keys($where);
    $where_val  = (array) array_shift($where);
    $q         .= " WHERE " . esc_sql(array_shift($where_keys)) . ' IN (';

    if (!in_array($where_format, array('%s', '%d'), TRUE)) {
      $where_format = '%s';
    }

    $escaped = array();

    foreach ($where_val as $val) {
      $escaped[] = $this->db->prepare($where_format, $val);
    }

    $q .= implode(', ', $escaped) . ')';

    return $this->db->query($q);
  }

  private function set_table($table)
  {
    if ($table !== null) $this->table = $table;
  }
}
