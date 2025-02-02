<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Pest\Mutate\Mutators\String\UnwrapStrRepeat;
use function Laravel\Prompts\alert;
use function Laravel\Prompts\select;

class MembersRespiceMemberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:respice {userEmail?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invalidate NFC signature by changing the pepper to a new random value';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(! ($this->hasOption('userEmail') && $user = User::whereEmail(strtolower($this->option('userEmail')))->first())){
            \Laravel\Prompts\info('no user transmitted or none found, please select:');
            $user = $this->getUser();
        }

        if($user instanceof User) {
            $user->respice()->save();
            \Laravel\Prompts\info('User successfully respiced! Old signature is now invalid');
            return self::SUCCESS;
        }
        alert('Could not respice any user :-(');

//        &&$user->pepper
//        $user->pepper
    }

    private function getUser()
    {
        $id = select(
            label: 'Select the user to be respiced',
            options: User::orderBy('email')->pluck('email', 'id')
        );
        return User::find($id);
    }
}
