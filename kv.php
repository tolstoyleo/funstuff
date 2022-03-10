<?php // coding exercise - build a key value store that stores a timestamp that allows a "between" lookup

class NoKeyFoundException extends \Exception
{

}

class KVStore
{

  private array $store = [];

  /**
   * add a value to the store.
   *
   * @param string|null
	 * @param mixed
   * @param mixed
   *
   * @return KVStore for method chaining
   */
  public function add(string $time = null, $key, $val): KVStore {
		
		$this->store[strtotime($time)][$key] = $val;
    
		return $this;
  }

	/**
	 * get a value from the store, if no time requested, return the first key
	 *
	 * @param string|null
	 * @param mixed
   *
	 * @return mixed
	 *
	 * @throws NoKeyFoundException
	 */
  public function get(string $time = null, $keyToGet) {
		
    // return an exact match if exists
    if (isset($this->store[strtotime($time)][$keyToGet])) {
      return $this->store[strtotime($time)][$keyToGet];
    }

		$length = count($this->store);
		$matchingKeys = [];
   
    // get the matching keys into their own array. if no time is requested, return the first matching key
		foreach($this->store as $ts => $key) {
      if (array_key_first($key) === $keyToGet) {
				if (!$time) {
          return $this->store[$ts][array_key_first($key)];
        }
        $matchingKeys[$ts] = $this->store[$ts];
			}
		}

		if (count($matchingKeys) === 0) {
			throw new NoKeyFoundException('No suitable match was found', 404);
  	}

		if (count($matchingKeys) === 1) {
			return $matchingKeys[0][$keyToGet];
		}

    // there are multiple matching keys, find the state of the key at the time requested
    ksort($matchingKeys);

    $matchingTs = null;

    foreach($matchingKeys as $ts => $val) {
      if (strtotime($time) > $ts) {
        $matchingTs = $ts;
      }
    }

    // if no matching time stamp, throw no key found exception, as the time being requested is before any such key=>value existed.
    if (!$matchingTs) {
      throw new NoKeyFoundException('No suitable match was found', 404);
    }

    return $this->store[$matchingTs][$keyToGet];

	}

  /**
   * delete a value or many values from the store
   *
   * @param string|null
   * @param mixed
   *
   */
  public function delete(string $time = null, $keyToDelete): bool {
    // delete single entry if found
    if ($time && isset($this->store[strtotime($time)][$keyToDelete])) {
      unset($this->store[strtotime($time)][$keyToDelete]);
      return true;
    }

    $deleted = 0;

    // delete all entries that match key
    if (!$time) {
      foreach($this->store as $ts => $key) {
        if (array_key_first($key) === $keyToDelete) {
          unset($this->store[$ts]);
          $deleted++;
        }
      }
      return ($deleted > 0);
    }

    return false;
  }

  public function getStore(): array {
    return $this->store;
  }
}

$kvstore = new KVStore;
$kvstore
  ->add("7 am", "key1", "test1")
  ->add("7:30 am", "key2", "test2")
  ->add("7:45 am", "key2", "test3")
  ->add("9 am", "key2", "test44")
  ->add("8 am", "key3", "test3")
  ->add("8:30 am", "key4", "test4")
  ->add("9 am", "key4", "test5");

try {
  var_dump($kvstore->get("9am", "key4")); // exact match
  var_dump($kvstore->get("7:40 am", "key2")); // between match
  var_dump($kvstore->delete("9am", "key4")); // delete single key at specific time
  var_dump($kvstore->delete(null, "key2")); // delete all matching keys
  var_dump($kvstore->getStore()); // dispaly the entire store at this moment
  var_dump($kvstore->get("7:30a", "key2")); // exception should be caught below since this key has been deleted
} catch (NoKeyFoundException $e) {
  var_dump($e->getMessage());
} catch (\Exception $e) {
  var_dump($e->getMessage());
}

/* outputs:

string(5) "test5"
string(5) "test2"
bool(true)
bool(true)
array(3) {
  [1646834400]=>
  array(1) {
    ["key1"]=>
    string(5) "test1"
  }
  [1646838000]=>
  array(1) {
    ["key3"]=>
    string(5) "test3"
  }
  [1646839800]=>
  array(1) {
    ["key4"]=>
    string(5) "test4"
  }
}
string(27) "No suitable match was found"

*/
