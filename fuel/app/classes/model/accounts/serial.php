<?php
use Orm\Model;

class Model_Accounts_Serial extends Model
{
	protected static $_properties = array(
        'id',
        'code',
        'name',
        'start',
        'next',
        'enabled',
        'fdesk_user',
		'created_at',
		'updated_at',
	);

	protected static $_table_name = 'document_serial';

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => true,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('code', 'Prefix', 'required|valid_string|max_length[3]');
		$val->add_field('name', 'Description', 'required');
		$val->add_field('start', 'Start', 'required|valid_string[numeric]');
		$val->add_field('next', 'Next', 'valid_string[numeric]');

		return $val;
	}

}
