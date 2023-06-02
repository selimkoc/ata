<?php

namespace Ata;

class Model
{
  // Properties
  public $table;
  public $db;

  protected function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
  }

  public function get($id)
  {
    $query = "SELECT * FROM " . $this->table . " WHERE id=%d";
    $query = $this->db->prepare($query, [$id]);
    return $this->db->get_row($query);
  }

  public function insertMultiple($fields, $values, $formats, $table = null)
  {

    if ($table === null) $table = $this->table;

    $query = "INSERT INTO " . $table . " (" . implode(',', $fields) . ")";

    foreach ($values as $j => $value)
      $values_string[] =  $this->db->prepare('(' . implode(',', $formats) . ')', $values[$j]);

    $query .= ' VALUES ' . implode(',', $values_string);

    return $this->db->query($query);
  }

  public function updateMultiple($data, $where, $format, $where_format, $table = null)
  {

    if ($table === null) $table = $this->table;

    $i          = 0;
    $q          = "UPDATE " . $table . " SET ";
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
}
