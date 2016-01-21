<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Rooms', function ($table) {
            $table->string('slug')->unique()->after('name');
            $table->boolean('private')->default(false)->after('slug');
            $table->boolean('permanent')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Rooms', function ($table) {
            $table->dropColumn('private');
            $table->dropColumn('slug');
        });
    }
}
