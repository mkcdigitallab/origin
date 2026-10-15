<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatClass extends Model
{
    protected $table='classes_etats';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'prochain_id', 'initialisee', 'taille_max', 'signature_source', 'cree_le', 'maximum_mixtes', 'score'];

}
