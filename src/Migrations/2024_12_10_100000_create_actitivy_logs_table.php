<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id()->comment('Id of the activity log.');
            $table->string('model_class')->comment('Target model.');
            $table->json('data')->nullable()->comment('List of changes (old and new values).');
            $table->foreignId('user_id')->comment('Id of the associated user.')->nullable()
                ->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('is_anonymous')->comment('If there is no user connected when action was realised.');
            $table->boolean('is_console')->comment('If the action was realised in console.');
            $table->integer('model_id')->comment('Id of the associated target model.');
            $table->integer('event')->comment('Event of this activity (ActivityLogsEventEnum).');
            $table->timestamp('created_at')->comment('The date on which the user was published.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
