<?php

namespace PopHub\Model;

class Pages {

  private $first;

  private $prev;

  private $next;

  private $last;

  /**
   * @param String $first
   * @param String $prev
   * @param String $next
   * @param String $last
   * @return void
   */
  public function __construct($first, $prev, $next, $last) {
    $this->first = $first;
    $this->prev = $prev;
    $this->next = $next;
    $this->last = $last;

    // Let's just use numbers and not URL's.
    $this->constructPaging($this);
  }

  /**
   * Get's the number of pages we have as an INT.
   * @return Integer
   */
  public function getNumPages() {
    return $this->last ? (int)$this->last : $this->prev + 1;
  }

  /**
   * @return String
   */
  public function getFirst() {
    return $this->first;
  }

  /**
   * @return String
   */
  public function getPrev() {
    return $this->prev;
  }

  /**
   * @return String
   */
  public function getnext() {
    return $this->next;
  }

  /**
   * @return String
   */
  public function getLast() {
    return $this->last;
  }

  /**
   * Removes the url from the items and just gets the numbers
   * @param Pages $pages
   * @return Pages object
   */
  public function constructPaging(Pages $pages) {
    foreach ($pages as $key => $page) {
      $pages->$key = preg_replace('/^.*page=\s*/', '', $pages->$key);
    }
    return $pages;
  }
}