<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GammuReceive extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'gammu:receive';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gammu run on receive script.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		// $VM             = new VisitorModel;
		// $VM->ip         = $this->argument('text');
		// $VM->id_article = $this->argument('hp');
		// $VM->type       = 1;
		// $VM->save();
		Helper::CallbackGammu($this->argument('hp'),$this->argument('text'));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('text', InputArgument::REQUIRED,'sms content'),array('hp', InputArgument::REQUIRED,'phone number'));
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
