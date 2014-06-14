<?php

/**
 * Core Model Class
 *
 * Provides methods to quickly to do CRUD operations
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 * @todo Events for 'before_save' and 'after_save'
 */
class MY_Model extends CI_Model {

    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $created = 'created';

    /**
     * @var string
     */
    protected $created_format = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected $modified = 'modified';

    /**
     * @var string
     */
    protected $modified_format = 'Y-m-d H:i:s';

    /**
     * @var array
     */
    protected $keyword_fields = array();

    /**
     * @var object
     */
    protected $pagination;

    /**
     * @var int
     */
    protected $per_page = 20;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var int
     */
    protected $page;

    /**
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Returns current table
     *
     * @return string
     */
    protected function get_table() {
        return $this->table;
    }

    /**
     * Saves a record
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function save(array $data, $id = null) {
        return $id != null ? $this->update($data, $id) : $this->create($data);
    }

    /**
     * Creates a record
     *
     * @param array $data
     * @return int
     */
    public function create(array $data) {
        // We need to update the timestamps
        $data = $this->update_timestamps($data, true);

        // Insert the record
        $this->db->insert($this->table, $data);

        // If successful, return the insert id
        return $this->db->affected_rows() > 0 ? $this->db->insert_id() : null;
    }

    /**
     * Updates a record
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, $id) {
        // We need to update the timestamps
        $data = $this->update_timestamps($data);

        // Update the record
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);

        // For consistency we return the id if save is successful
        return $this->db->affected_rows() > 0 ? $id : null;
    }

    /**
     * Update the records timestamps
     *
     * @param array $data
     * @param bool $created
     * @return array
     */
    private function update_timestamps(array $data, $created = false) {
        // Set modified field
        if((string)$this->modified !== '') {
            $data[$this->modified] = date($this->modified_format);
        }

        // Update created if enabled
        if($created && (string)$this->created !== '') {
            $data[$this->created] = date($this->created_format);
        }

        // Return the data back
        return $data;
    }

    /**
     * Find record(s)
     *
     * @param mixed $type 'first' to get first record, 'all' to get all records, 'count' to return the count of records found, a numeric value to do a find by ID
     * @param array $options
     */
    public function find($type = 'all', array $options = array()) {
        // Merge our options with the default ones
        $options = $this->merge_default_find_options($options);

        // If type is an ID move it into the where clause and set type as first
        if(is_numeric($type)) {
            $options['where'][] = array($this->table.'.id', $type);
            $type = 'first';
        }

        // Setup pagination
        if($options['paginate'] === true) {
            // We need to remove some options for the count
            $count_options = $options;

            // Ensure we don't paginate a count
            $count_options['paginate'] = false;

            // Don't set an order
            if(isset($count_options['order_by'])) unset($count_options['order_by']);

            // Get the the results count
            $this->count = $this->find('count', $count_options);

            // Assign the page number if its set
            if(is_numeric($options['page'])) $this->page = $options['page'];

            // Determine page numbers for pagination
            $this->set_pagination();

            // Set the limit for pagination
            if(is_object($this->pagination)) {
                $options['limit'][] = array($this->pagination->per_page, $this->pagination->first_page - 1);
            }
        }

        // Do we need to set the keywords?
        if(isset($options['keywords'])) $options['where'][] = $this->set_keywords($options['keywords']);

        // Build the query
        $query = $this->build_query($options);

        // Return data based on set type
        switch($type) {
            case 'all':
                $method = $options['return_as'] == 'array' ? 'result_array' : 'result';
                return $query->get($this->table)->$method();
            case 'first':
                $method = $options['return_as'] == 'array' ? 'row_array' : 'row';
                return $query->get($this->table)->$method();
            case 'count':
                return $query->count_all_results($this->table);
            default:
                return null;
        }
    }

    /**
     * Merges the default find options with the current ones
     *
     * @param array $options
     * @return array
     */
    private function merge_default_find_options(array $options) {
        return array_merge($this->default_find_options(), $options);
    }

    /**
     * Returns the default find options
     *
     * @return array
     */
    private function default_find_options() {
        return array(
            'paginate' => false,
            'page' => $this->page,
            'per_page' => $this->per_page,
            'return_as' => 'object',
            'select' => array(),
            'where' => array(),
            'where_in' => array(),
            'where_not_in' => array(),
            'join' => array(),
            'order_by' => array(),
            'limit' => array()
        );
    }

    /**
     * Compiles the database query
     *
     * @todo allow overrides for methods be created eg set_where
     * @param array $options
     * @return object
     */
    private function build_query(array $options) {
        foreach($options as $method => $option) {
            // Check the method exists in the db class and that the value is an array
            if(method_exists($this->db, $method) && is_array($option) && !empty($option)) {
                foreach($option as $parameters) {
                    // Call the method
                    call_user_func_array(array($this->db, $method), $parameters);
                }
            }
        }

        return $this->db;
    }

    /**
     * Set the query keywords
     *
     * @param string $keywords
     * @return array
     */
    private function set_keywords($keywords) {
        if(empty($this->keyword_fields) || (string)$keywords === '') array();

        $i = 0;
        $where = '';
        foreach($this->keyword_fields as $field) {
            if($i === 0) {
                $where .= '(';
            }

            $where .= $field.' LIKE '.$this->db->escape('%'.$keywords.'%');

            if($i === (count($this->keyword_fields) - 1)) {
                $where .= ')';
            } else {
                $where .= ' OR ';
            }

            $i++;
        }

        return $where !== '' ? array($where) : array();
    }

    /**
     * Sets up the pagination
     *
     * @return array
     */
    private function set_pagination() {
        // A result count is required
        if(!$this->count) {
            return null;
        }

        // If page is not set lets try to get it from the query string
        if(!$this->page) {
            $this->page = $this->input->get('page') ? $this->input->get('page') : 1;
        }

        // Create the pagination object
        $this->pagination = new stdClass;
        $this->pagination->current_page = (int)$this->page;
        $this->pagination->per_page = $this->per_page;
        $this->pagination->first_page = (($this->page - 1) * $this->per_page) + 1;
        $this->pagination->last_page = min((($this->page) * $this->per_page), $this->count);
        $this->pagination->total_pages = ceil($this->count / $this->per_page);
        $this->pagination->total_items = $this->count;

        return $this->pagination;
    }

    /**
     * Returns the pagination
     *
     * @return object
     */
    public function pagination() {
        return $this->pagination;
    }

    /**
     * Deletes records
     *
     * @see $this->find()
     * @param mixed $type
     * @param array $options
     * @return bool
     */
    public function delete($type = 'all', array $options = array()) {
        // Search for records to delete
        $records = $this->find($type, $options + array('return_as' => 'object'));

        // If a singular is returned make sure its in a loopable array
        if(is_object($records)) $records = array($records);

        // Get ids of records to be deleted
        $ids = array();
        foreach($records as $record) {
            array_push($ids, $record->id);
        }

        if(!empty($ids)) {
            $this->db->where_in('id', $ids)->delete($this->table);

            $deleted = $this->db->affected_rows() > 0;
        }

        return isset($deleted) ? $deleted : false;
    }

}
