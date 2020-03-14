<?php 
	//require_once 'app/models/DAOs/tools/DataObjectPDO.class.php';
    require_once 'app/models/tools/DataBaseFactory.class.php';

	/**
	 * 
	 */
	abstract class DataAccessObjectMongo 
	{
        protected $collection;
        protected $cursor;
        protected $options = [];
        protected $defaultOptions = [];

        public function setOption(string $option, $value){
            $defaultOptions[$option] = $value;
        }
        public function getOption(string $option){
            return $defaultOptions[$option];
        }
        public function removeOption(string $option){
            unset($defaultOptions[$option]);
        }

        protected function __construct(string $collection) {
            $this->collection = DataBaseFactory::getDataBase("mongo")->getConnection($collection);
        }

        protected function mergeOptions(array ... $options):array{
            $this->options = array_merge($this->defaultOptions, ...$options);
            return $this->options; 
        }

        public function find(array $filter = [], array $options = [])
        {
            $this->cursor = $this->collection->find($filter, array_merge($options, $this->defaultOptions));
            return iterator_to_array($this->cursor, false);
        }

        public function findById(string $id, array $options = []) 
        {
            return $this->collection->findOne(["_id" => new MongoDB\BSON\ObjectId("$id")], array_merge($options, $this->defaultOptions));
        }
        public function insertOne($document, array $options = []) : string
        {
            return (string) $this->collection->insertOne($document, array_merge($options, $this->defaultOptions))->getInsertedId();;
        }

        public function deleteById(string $id, array $options = [])
        {
            return $this->deleteOne(["_id" => new MongoDB\BSON\ObjectId("$id")], $options);
        }

        public function deleteOne(array $filter, array $options = [])
        {
            return $this->collection->deleteOne($filter, $options)->getDeletedCount() == 1;
        }

        public function updateOneById(string $id, array $update, array $options = [])
        {
            return $this->updateOne(["_id" => new MongoDB\BSON\ObjectId("$id")], $update, $options);
        }
        public function updateOne(array $filter, array $update,  array $options = [])
        {
            return $this->collection->updateOne($filter, [ '$set' => $update], $options)->getModifiedCount() == 1;
        }
        
		

	}


?>