<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Core Model Class
 *
 * Provides methods to rapidly build CRUD systems
 *
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class MY_Model extends CI_Model {

    /**
     * Table Name
     * 
     * @var string
     */
    protected $table;

    /**
     * Table primary key
     *
     * @var string
     */
    protected $primary = 'id';

    /**
     * Created date field name
     * 
     * @var string
     */
    protected $created = 'created';

    /**
     * Created date format
     * 
     * @var string
     */
    protected $created_format = 'Y-m-d H:i:s';

    /**
     * Modified date field name
     * 
     * @var string
     */
    protected $modified = 'modified';

    /**
     * Modified date format
     * 
     * @var string
     */
    protected $modified_format = 'Y-m-d H:i:s';

    /**
     * Fields to search when using the search filter
     * 
     * @var array
     */
    protected $searchable = array();

    /**
     * Table fields that contain validation rules
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Generated pagination object
     * 
     * @var object
     */
    protected $pagination;

    /**
     * Results per page
     * 
     * @var int
     */
    protected $per_page = 20;

    /**
     * Result count
     * 
     * @var int
     */
    protected $count;

    /**
     * Current page
     * 
     * @var int
     */
    protected $page;

    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the table name
     *
     * @return string
     */
    protected function table()
    {
        return $this->table;
    }

    /**
     * Gets the table primary key
     *
     * @return string
     */
    protected function primary()
    {
        return $this->primary;
    }

    /**
     * Saves a record
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function save(array $data, $id = NULL)
    {
        return $id != NULL ? $this->update($data, $id) : $this->create($data);
    }

    /**
     * Creates a record
     *
     * @param array $data
     * @return int
     */
    public function create(array $data)
    {
        // Do common pre save tasks
        $data = $this->pre_save($data, TRUE);

        // Fire before create and before save event
        $this->trigger('before_create', $data);
        $this->trigger('before_save', $data);

        // Insert the record
        $this->db->insert($this->table, $data);

        // If successful, we will be returning the records id
        $data['id'] = $this->db->affected_rows() > 0 ? $this->db->insert_id() : NULL;

        if($data['id'])
        {
            $this->trigger('after_create', $data);
            $this->trigger('after_save', $data);
        }

        return $data['id'];
    }

    /**
     * Updates a record
     *
     * @param array $data
     * @param int $id
     * @return int
     */
    public function update(array $data, $id)
    {
        // Do common pre save tasks
        $data = $this->pre_save($data);

        // Fire before update and before save event
        $this->trigger('before_update', $data);
        $this->trigger('before_save', $data);

        // Update the record
        $this->db->where($this->primary, $id);
        $this->db->update($this->table, $data);

        // For consistency we return the id if save is successful
        $data['id'] = $this->db->affected_rows() > 0 ? $id : NULL;

        if($data['id'])
        {
            $this->trigger('after_create', $data);
            $this->trigger('after_save', $data);
        }

        return $data['id'];
    }

    /**
     * Update the records timestamps
     *
     * @param array $data
     * @param bool $created
     * @return array
     */
    private function update_timestamps(array $data, $created = FALSE)
    {
        // Set modified field
        if((string)$this->modified !== '')
        {
            $data[$this->modified] = date($this->modified_format);
        }

        // Update created if enabled
        if($created && (string)$this->created !== '')
        {
            $data[$this->created] = date($this->created_format);
        }

        // Return the data back
        return $data;
    }

    /**
     * Common tasks to do before we save a record
     *
     * @param array $data Data to be saved
     * @param bool $new Is this a new record
     * @return array
     */
    private function pre_save(array $data, $new = FALSE)
    {
        // Filter the data to be saved
        $data = $this->filter_fields($data);

        // We need to update the timestamps
        $data = $this->update_timestamps($data, $new);

        // Retun updated data
        return $data;
    }

    /**
     * Find record(s)
     *
     * @param mixed $type 'first' to get first record, 'all' to get all records, 'count' to return the count of records found, a numeric value to do a find by ID
     * @param array $options
     */
    public function find($type = 'all', array $options = array())
    {
        // Merge our options with the default ones
        $options = $this->merge_default_find_options($options);

        // If type is an ID move it into the where clause and set type as first
        if(is_numeric($type))
        {
            $options['where'][] = array($this->table.'.id', $type);
            $type = 'first';
        }

        // Setup pagination
        if($options['paginate'] === TRUE)
        {
            // We need to remove some options for the count
            $count_options = $options;

            // Ensure we don't paginate a count
            $count_options['paginate'] = FALSE;

            // Don't set an order
            if(isset($count_options['order_by']))
            {
                unset($count_options['order_by']);
            }

            // Get the the results count
            $this->count = $this->find('count', $count_options);

            // Assign the page number if its set
            if(is_numeric($options['page']))
            {
                $this->page = $options['page'];
            }

            // Determine page numbers for pagination
            $this->set_pagination();

            // Set the limit for pagination
            if(is_object($this->pagination))
            {
                $options['limit'][] = array($this->pagination->per_page, $this->pagination->first_page - 1);
            }
        }

        // Do we need to set the search?
        if(isset($options['search']))
        {
            $options['where'][] = $this->set_search($options['search']);
        }

        // Fire before find event
        $this->trigger('before_find', $options);

        // Build the query
        $query = $this->build_query($options);

        // Find data based on set type
        switch($type)
        {
            case 'all':
                $method = $options['return_as'] === 'array' ? 'result_array' : 'result';
                $result = $query->get($this->table)->$method();
            case 'first':
                $method = $options['return_as'] === 'array' ? 'row_array' : 'row';
                $result = $query->get($this->table)->$method();
            case 'count':
                $result = $query->count_all_results($this->table);
            default:
                $result = NULL;
        }

        // After find event
        $this->trigger('after_find', $result);

        // Return result
        return $result;
    }

    /**
     * Merges the default find options with the current ones
     *
     * @param array $options
     * @return array
     */
    private function merge_default_find_options(array $options)
    {
        return array_merge($this->default_find_options(), $options);
    }

    /**
     * Returns the default find options
     *
     * @return array
     */
    private function default_find_options()
    {
        return array(
            'paginate' => FALSE,
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
    private function build_query(array $options)
    {
        foreach($options as $method => $option)
        {
            // Check the method exists in the db class and that the value is an array
            if(method_exists($this->db, $method) && is_array($option) && !empty($option))
            {
                foreach($option as $parameters)
                {
                    // Call the method
                    call_user_func_array(array($this->db, $method), $parameters);
                }
            }
        }

        return $this->db;
    }

    /**
     * Set the queries search keywords
     *
     * @param string $keywords
     * @return array
     */
    private function set_search($keywords)
    {
        if(empty($this->searchable) OR (string)$keywords === '')
        {
            return array();
        }

        $i = 0;
        $where = '';
        foreach($this->searchable as $field)
        {
            if($i === 0)
            {
                $where .= '(';
            }

            $where .= $field.' LIKE '.$this->db->escape('%'.$keywords.'%');

            if($i === (count($this->searchable) - 1))
            {
                $where .= ')';
            }
            else
            {
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
    private function set_pagination()
    {
        // A result count is required
        if( ! $this->count) {
            return NULL;
        }

        // If page is not set lets try to get it from the query string
        if( ! $this->page)
        {
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
    public function pagination()
    {
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
    public function delete($type = 'all', array $options = array())
    {
        // Search for records to delete
        $records = $this->find($type, $options + array('return_as' => 'object'));

        // If a singular is returned make sure its in a loopable array
        if(is_object($records))
        {
            $records = array($records);
        }

        // We need to know what our primary field is for the id
        $primary = $this->primary();

        // Get ids of records to be deleted
        $ids = array();
        foreach($records as $record)
        {
            array_push($ids, $record->$primary);
        }

        if( ! empty($ids))
        {
            // Trigger before delete
            $this->trigger('before_delete', $ids);

            // Do delete process
            $this->db->where_in($primary, $ids)->delete($this->table);
            $deleted = $this->db->affected_rows() > 0;

            // Trigger the after delete if successful
            if ($deleted)
            {
                $this->trigger('after_delete');
            }
        }

        return isset($deleted) ? $deleted : FALSE;
    }

    /**
     * Shortcut for the 'on' model events
     *
     * @param string $event
     * @param string $method
     * @return void
     */
    public function on($event, $method)
    {
        // Get the correct model name
        $model = ucfirst(strtolower($this->table)).'_model';

        // Register the event
        $this->event->on($model.'.'.$event, $model, $method);
    }

    /**
     * Shortcut for the 'trigger' model events
     */
    public function trigger($event)
    {
        // Get the correct model name
        $model = ucfirst(strtolower($this->table)).'_model';

        // Execute the event
        return $this->event->trigger($model.'.'.$event);
    }

    /**
     * Validate input based on the models rules
     * 
     * @param array $data Data to be validated
     * @return bool
     */
    public function validate(array $data = array()) 
    {
        // No need to do anything if we have no rules
        if (empty($this->fields))
        {
            return true;
        }

        // Set the rules
        foreach($this->fields as $field => $config)
        {
            $title = isset($config['title']) ? $config['title'] : '';
            $rules = isset($config['rules']) ? $config['rules'] : '';

            if ( ! empty($title) && ! empty($rules))
            {
                $this->form_validation->set_rules($field, $title, $rules);
            }
        }

        // Set the data
        $this->form_validation->set_data($config);

        // Run the validation
        return $this->form_validation->run();
    }

    /**
     * Filters out keys that are not in the fields array
     *
     * @param array $data Data to be filtered
     * @return array Filtered data
     */
    public function filter_fields(array $data = array())
    {
        $filtered = array();

        foreach($data as $key => $value)
        {
            // Add it to the filter array if the key exists
            if(array_key_exists($key, $this->fields))
            {
                $filtered[$key] = $value;
            }
        }

        // Return filtered data
        return $filtered;
    }

}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */