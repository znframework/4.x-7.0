<?php
class InternalMigrateUserActivities extends ZN\Database\InternalMigration
{
	//--------------------------------------------------------------------------------------------------------
	// Class/Table Name
	//--------------------------------------------------------------------------------------------------------
	const table = 'user_activities';

	//--------------------------------------------------------------------------------------------------------
	// Up
	//--------------------------------------------------------------------------------------------------------
	public function up()
	{
		// Default Query
		return $this->createTable
		([
		    'id'       => [DB::int(11), DB::primaryKey(), DB::autoIncrement()],
			'user_id'  => [DB::int(11)],
			'activity' => [DB::varchar(250)],
			'date'     => [DB::datetime(), 'DEFAULT CURRENT_TIMESTAMP']
		]);
	}

	//--------------------------------------------------------------------------------------------------------
	// Down
	//--------------------------------------------------------------------------------------------------------
	public function down()
	{
		// Default Query
		return $this->dropTable();
	}
}