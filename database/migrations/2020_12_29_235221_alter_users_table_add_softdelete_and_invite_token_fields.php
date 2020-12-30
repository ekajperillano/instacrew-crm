<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddSoftdeleteAndInviteTokenFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
            $table->string('invite_token')->nullable()->after('password');
            $table->date('created_invite_token_at')->nullable()->after('invite_token');
            $table->string('deleted_by')->nullable()->after('deleted_at');
            $table->string('created_by')->nullable()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('created_invite_token_at');
            $table->dropColumn('deleted_at');
            $table->dropColumn('deleted_by');
            $table->dropColumn('created_by');
            $table->dropColumn('invite_token');
        });
    }
}
