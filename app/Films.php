<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Auth;

class Films extends Authenticatable {

    use SoftDeletes;
    use Notifiable;

    /**
     * The database table and primary key used by the model.
     * @var string
     */
    protected $table;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];


    /**
     * Create a new model instance.
     * @return void
     */
    public function __construct() {
        $this->table = config('constant.db_table.FILMS');
    }
    /**
     * @var array
     */
    public static function addFilmValidationRules() {
        return [
            'film_name'    => 'required',
            'desc'     => 'required',
            'release_date'  => 'required',
            'rating'     => 'required',
            'price'  => 'required',
            'country'  => 'required',
            'genre'  => 'required',
            'photo'  => 'required',
        ];
    }
    public static $addFilmValidationMessages = [
        'film_name.required'          => 'Please enter name',
        'desc.required'               => 'Please enter description',
        'release_date.required'       => 'Please enter date',
        'rating.required'             => 'Please select rating',
        'price.required'              => 'Please enter price',
        'country.required'            => 'Please select rating',
        'genre.required'              => 'Please select genre',
        'photo.required'              => 'Please upload photo'
    ];

    public static function updateFilmValidationRules() {
        return [
            'film_name'    => 'required',
            'desc'     => 'required',
            'release_date'  => 'required',
            'rating'     => 'required',
            'price'  => 'required',
            'country'  => 'required',
            'genre'  => 'required'
        ];
    }

    /**
     * Validation messages
     *
     * @var array
     */
    public static $validationMessages = [
        'comment.required'         => 'Please enter comment',
        'comment.max'              => 'Comment can be max 250 characters long',
        'film_id.required'         => 'Please enter film id',
    ];

    /**
     * Validation rules for add comment
     *
     * @return array
     */
    public static function validationRules() {
        return [
            'comment' => 'required|max:250',
        ];
    }

    /**
     * Get film data.
     *
     * @param
     * @return array
     */
    public static function getFilmData($slug) {
        return $filmDetails = self::Select('id', 'name', 'slug', 'description', 'release_date', 'rating', 'ticket_price', 'photo')
            ->where('slug',$slug)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * Get film all comments.
     *
     * @param
     * @return array
     */
    public static function getFilmComments($id) {
        return $commentList = DB::table(config('constant.db_table.COMMENTS'))->Select(config('constant.db_table.COMMENTS').'.id', 'comments', 'name')
            ->join(config('constant.db_table.USERS'),config('constant.db_table.USERS').'.id','=',config('constant.db_table.COMMENTS').'.user_id')
            ->where(config('constant.db_table.COMMENTS').'.film_id',$id)
            ->whereNull(config('constant.db_table.COMMENTS').'.deleted_at')
            ->get();
    }

    /**
     * Save comment in database
     * @return array
     */
    public static function saveComment($data) {
        return $response = DB::table(config('constant.db_table.COMMENTS'))->insert(['comments' => $data['comment'], 'film_id' => $data['film_id'], 'user_id' => Auth::user()->id]);
    }

    /* Get film data.
    * @param
    * @return array
    */
    public static function getFilms($id) {
        return $filmDetails = self::Select('id', 'name', 'slug', 'description', 'release_date', 'rating', 'ticket_price', 'photo','country','genre')
            ->where('id',$id)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * Get film list from database
     * @return array
     */
    public static function getFilmList() {
        return $response = self::Select('id','name','description','release_date','rating','ticket_price','photo','country','genre')->whereNull('deleted_at');
    }

    /**
     * deleteFilm
     * @return array
     */
    public static function deleteFilm($id) {
        $film = self::find($id);
        $film->delete();
    }
    //
}
