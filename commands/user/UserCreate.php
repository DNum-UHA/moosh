<?php
/**
 * moosh user-create [--password=<password> --email=<email>
 *                   --city=<city> --country=<CN>
 *                   --firstname=<firstname> --lastname=<lastname>]
 *                   <username1> [<username2> ...]
 */
class UserCreate extends MooshCommand
{
    public function __construct()
    {
        parent::__construct('create', 'user');
        $this->addOption('p|password:');
        $this->addOption('e|email:');
        $this->addOption('c|city:');
        $this->addOption('C|country:');
        $this->addOption('f|firstname:');
        $this->addOption('l|lastname:');

        $this->addRequiredArgument('username');
        $this->maxArguments = 255;
    }

    public function execute()
    {
        global $CFG, $DB;

        require_once $CFG->dirroot . '/user/lib.php';
        unset($CFG->passwordpolicy);

        $options = $this->expandedOptions;
        foreach ($this->arguments as $argument) {
            $this->expandOptionsManually(array($argument));
            $user = new stdClass();
            $user->password = $options['password'];
            $user->email = $options['email'];
            $user->city = $options['city'];
            $user->country = $options['country'];
            $user->firstname = $options['firstname'];
            $user->lastname = $options['lastname'];
            $user->timecreated = time();
            $user->timemodified = $user->timecreated;
            $user->username = $argument;

            $user->confirmed = 1;
            $user->mnethostid = $CFG->mnet_localhost_id;

            //either use API user_create_user
            $newuserid = user_create_user($user);

            //or direct insert into DB
            //$user->password = md5($this->expandedOptions['password']);
            //$newuserid = $DB->insert_record('user', $user);

            echo "$newuserid\n";
        }
    }
}