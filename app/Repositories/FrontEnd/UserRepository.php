<?php 

namespace App\Repositories\FrontEnd;

use App\Models\User;
use Carbon\Carbon;
use DB;

class UserRepository {

    private $model;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index() {
        try {
            return $this->model->get();
        } catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }

    public function create($input) {
        try {
            DB::beginTransaction();
            $user = $this->model->create($input);

            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            return false;
        }
    }
}