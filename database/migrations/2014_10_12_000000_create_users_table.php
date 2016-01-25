<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->integer('age');
            $table->enum('sex',['Male','Female']);
            $table->enum('marital_statud',['Married','Not Married']);
            $table->string('relative_name');
            $table->string('occupation');
            $table->text('address');
            $table->string('mobile');
            
            $table->boolean('good_health');
            
            $table->boolean('regular_physician');
            $table->string('physician_name')->nullable();
            $table->text('physician_address')->nullable();
            
            $table->boolean('under_treatment');
            $table->text('health_condition')->nullable();
            $table->text('treatment')->nullable();

            $table->boolean('high_bp')->default(false);
            $table->boolean('asthma')->default(false);
            $table->boolean('sinusitis')->default(false);
            $table->boolean('persistant_headache')->default(false);
            $table->boolean('seizures')->default(false);
            $table->boolean('tuberculosis')->default(false);
            $table->boolean('low_bp')->default(false);
            $table->boolean('allergy')->default(false);
            $table->boolean('chest_pain')->default(false);
            $table->boolean('heart_attack')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('anemia')->default(false);
            $table->text('drugs')->nullable();

            $table->boolean('serious_illness')->default(false);
            $table->text('serious_illness_problem');

            $table->boolean('dental_treatment_earlier')->default(false);
            $table->text('filling')->nullable();
            $table->text('root_canal')->nullable();
            $table->text('extraction')->nullable();
            $table->text('denture')->nullable();
            $table->text('scaling')->nullable();

            $table->boolean('normal_bleeding')->default(true);
            $table->boolean('serious_problems')->default(false);

            $table->boolean('medicines_without_prescription')->default(false);
            $table->text('medicines')->nullable();

            $table->boolean('penecillin')->default(false);
            $table->boolean('local_anaesthetics')->default(false);
            $table->boolean('sulpha_drugs')->default(false);
            $table->boolean('aspirin')->default(false);
            $table->string('other')->nullable();

            $table->boolean('pregnant')->default(false);
            $table->text('complaint')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
