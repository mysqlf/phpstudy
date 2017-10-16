<?php  
    namespace MongoOrm;
    use Illuminate\Database\Capsule\Manager as DB;
    use Jenssegers\Mongodb\Eloquent\HybridRelations;
    class PostLogModel extends MoloquentModel
    {
        //必须 表名
        public $collection = 'post_log';

        public $connection = 'mongodb';

        public $primaryKey = "_id";

        //非必须，如果使用软删除就是必须，需要数据库表中存在字段：created_at updated_at deleted_at
        public $timestamps = false;

    }
?>
