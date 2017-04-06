<?php
/**
 * Библиотека для работы с базой данных MySQL
 *
 * @package   goDB
 * @version   1.3.2 (31 января 2011)
 * @link      http://pyha.ru/go/godb/
 * @author    Григорьев Олег aka vasa_c (http://blgo.ru/blog/)
 * @copyright &copy; Григорьев Олег & PyhaTeam, 2007-2010
 * @license   http://www.gnu.org/copyleft/lesser.html LGPL
 * @uses      php_mysqli (http://php.net/mysqli)
 */

class goDB extends mysqli implements goDBI
{


  /*** PUBLIC: ***/

    /**
     * Конструктор.
     *
     * Отличия от конструктора mysqli (http://php.net/manual/en/mysqli.connect.php):
     * 1. Исключение при ошибке подключения
     * 2. Дополнительный формат вызова: один аргумент - массив параметров
     * 3. Установка кодировки
     *
     * @link http://pyha.ru/go/godb/connect/
     *
     * @throws goDBExceptionConnect
     *         не подключиться или не выбрать базу
     *
     * @param mixed $host [optional]
     *        хост для подключения (возможен вариант "host:port")
     *        либо массив всех параметров
     * @param string $username [optional]
     *        имя пользователя mysql
     * @param string $passwd [optional]
     *        пароль пользователя mysql
     * @param string $dbname [optional]
     *        имя базы данных
     * @param int $port [optional]
     *        порт для подключения
     *        в случае указания аргументом и в строке $host используется аргумент
     * @param string $socket [optional]
     *        mysql-сокет для подключения
     */
    public function __construct($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null) {
		if (is_array($host)) {
            $config = $host;
            $fields = array(
                'host', 'username', 'passwd', 'dbname', 'port',
                'socket', 'charset', 'debug', 'prefix',
            );
            foreach ($fields as $field) {
                $$field = isset($config[$field]) ? $config[$field] : null;
            }
    		$this->setPrefix($prefix);
   			$this->setDebug($debug);
    	}
    	if (!$port) {
        	$host = explode(':', $host, 2);
        	$port = empty($host[1]) ? null : $host[1];
        	$host = $host[0];
    	}
        @parent::__construct($host, $username, $passwd, $dbname, $port, $socket);
        if (mysqli_connect_errno()) {
            throw new goDBExceptionConnect(mysqli_connect_error(), mysqli_connect_errno());
        }
        if (!empty($charset)) {
            $this->set_charset($charset);
        }
    }

    /**
     * Выполнение запроса к базе
     *
     * @link http://pyha.ru/go/godb/query/
     *
     * @throws goDBExceptionQuery
     *         ошибка при запросе
     * @throws goDBExceptionData
     *         ошибочный шаблон или входные данные
     * @throws goDBExceptionFetch
     *         неизвестный или неожиданный формат представления
     *
     * @param string $pattern
     *        sql-запрос или строка-шаблон с плейсхолдерами
     * @param array $data [optional]
     *        массив входных данных
     * @param string $fetch [optional]
     *        требуемый формат представления результата
     * @param string $prefix [optional]
     *        префикс имен таблиц
     * @return mixed
     *         результат запроса в заданном формате
     */
    public function query($pattern, $data = null, $fetch = null, $prefix = null) {
		self::$qQuery++;
    	$query = $this->makeQuery($pattern, $data, $prefix);
        if ($this->queryWrapper) {
            $query = call_user_func_array($this->queryWrapper, array($query));
            if (!$query) {
                return false;
            }
        }
        if ($this->transactionFailed) {
            return false;
        }
        $duration = microtime(true);
        $result = parent::query($query, MYSQLI_STORE_RESULT);
        $duration = microtime(true) - $duration;
        $this->toDebug($query, $duration);
        if ($this->errno) {
            if (is_object($result)) {
                $result->free();
            }
            throw new goDBExceptionQuery($query, $this->errno, $this->error);
        }
        $return = $this->fetch($result, $fetch);
        if ((is_object($result)) and ($result !== $return) and (!$this->isiterator)) {
            $result->free();
        }
        return $return;
    }

    /**
     * Выполнение запроса, путём вызова объекта, как метода
     *
     * @example $result = $db($pattern, $data, $fetch);
     * PHP >5.3
     */
    public function __invoke($pattern, $data = null, $fetch = null, $prefix = null) {
        return $this->query($pattern, $data, $fetch, $prefix);
    }

    /**
     * Старт транзакции
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @param bool $saveAC [optional]
     *        сохранять ли значение autocommit
     * @return int
     *         новый уровень вложенности
     */
    public function transactionBegin($saveAC = false) {
        $this->transactionLevel++;
        if ($this->transactionLevel > 1) {
            return $this->transactionLevel;
        }
        if ($saveAC) {
            $this->transactionACStored = $this->getAutocommit();
        } else {
            $this->transactionACStored = true;
        }
        parent::autocommit(false);
        $this->transactionFailed = false;
        return $this->transactionLevel;
    }

    /**
     * Подтверждение правильности данной транзакции
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return bool
     *         произошло ли сохранение или это вложенная транзакция
     */
    public function transactionCommit() {
        if (!$this->inTransaction()) {
            return false;
        }
        $this->transactionLevel--;
        if ($this->transactionLevel > 0) {
            return false;
        }
        if (!$this->transactionFailed) {
            parent::commit();
        } else {
            parent::rollback();
            $this->transactionFailed = false;
        }
        parent::autocommit($this->transactionACStored);
        return true;
    }

    /**
     * Откат транзакции
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @throws goDBExceptionTransactionRollback
     *
     * @param bool $throw [optional]
     *        false - тихий откат
     *        true  - с выбросом исключения
     */
    public function transactionRollback($throw = false) {
        if (!$this->inTransaction()) {
            return false;
        }
        if ($throw) {
            $this->transactionLevel = 0;
            parent::rollback();
            parent::autocommit($this->transactionACStored);
            $this->transactionFailed = false;
            throw new goDBExceptionTransactionRollback();
        } else {
            $this->transactionLevel--;
            if ($this->transactionLevel == 0) {
                parent::rollback();
                parent::autocommit($this->transactionACStored);
                $this->transactionFailed = false;
                return true;
            }
            $this->transactionFailed = true;
        }
        return true;
    }

    /**
     * Выполнение функции внутри транзакции

     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @throws любые исключения из функции,
     *         кроме goDBExceptionTransactionRollback на верхнем уровне
     *
     * @param callback $callback
     *        функция, вызываемая внутри транзакции
     * @param bool $saveAC [optional]
     *        сохранять ли значение автокоммита
     */
    public function transactionRun($callback, $saveAC = false) {
        $level = $this->transactionBegin($saveAC);
        try {
            $result = call_user_func($callback);
            if ($result) {
                $this->transactionCommit();
            } else {
                $this->transactionRollback();
            }
            return $result;
        } catch (goDBExceptionTransactionRollback $e) {
            return false;
        } catch (goDBExceptionQuery $e) {
            $this->transactionRollback();
            throw $e;
        }
    }

    /**
     * Узнать уровень вложенности транзакции
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return int
     *         уровень вложенности (1 - верхний, 0 - вне транзакции)
     */
    public function transactionLevel() {
        return $this->transactionLevel;
    }

    /**
     * Узнать, не отмечена ли траназкция уже, как провалившаяся
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return bool
     */
    public function transactionFailed() {
        return $this->transactionFailed;
    }

    /**
     * Узнать, находимся ли мы внутри транзакции
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return bool
     */
    public function inTransaction() {
        return ($this->transactionLevel > 0);
    }

    /**
     * Закрыть транзакцию, вне зависимости от уровня
     *
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @param bool $commit [optional]
     *        коммитить или откатывать
     */
    public function transactionClose($commit = false) {
        if (!$this->inTransaction()) {
            return false;
        }
        $this->transactionLevel = 1;
        if ($commit) {
            return $this->transactionCommit();
        } else {
            return $this->transactionRollback();
        }
    }

    /**
     * Переопределение mysqli::autocommit()
     *
     * @link http://ru.php.net/manual/en/mysqli.autocommit.php
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @param bool $mode
     *
     * @return bool
     */
    public function autocommit($mode) {
        if (!$this->inTransaction()) {
            return parent::autocommit($mode);
        }
        return true;
    }

    /**
     * Переопределение mysqli::commit()
     *
     * @link http://ru.php.net/manual/en/mysqli.commit.php
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return bool
     */
    public function commit() {
        if (!$this->inTransaction()) {
            return parent::commit();
        }
        return true;
    }

    /**
     * Переопределение mysqli::commit()
     *
     * @link http://ru.php.net/manual/en/mysqli.rollback.php
     * @link http://pyha.ru/go/godb/transaction/
     *
     * @return bool
     */
    public function rollback() {
        if (!$this->inTransaction()) {
            return parent::rollback();
        }
        $this->transactionLevel++;
        return $this->transactionRollback();
    }

    /**
     * Получить значение автокоммита
     *
     * @return bool
     */
    public function getAutocommit() {
        return $this->query('SELECT @@autocommit', null, 'bool');
    }


    /**
     * Мультизапрос
     *
     * @link http://pyha.ru/go/godb/multi/
     *
     * @param mixed $patterns
     * @param array $datas [optional]
     * @param mixed $fetches [optional]
     * @param bool $transaction [optional]
     * @return array
     */
    public function multiQuery($patterns, $datas = null, $fetches = null, $transaction = true) {
        if ($this->transactionFailed) {
            return false;
        }
        $args = $this->argsMultiQuery($patterns, $datas, $fetches);
        if (!$args) {
            return array();
        }
        $queries = $args['queries'];
        $last    = $args['last'];
        $multi   = array();
        foreach ($queries as $q) {
            $multi[] = $this->makeQuery($q[0], $q[1]).';';
        }
        $multi = implode("\n", $multi);
        $this->toDebug("\n".$multi."\n");
        if ($transaction) {
            $this->transactionBegin();
        }
        $this->multi_query($multi);
        try {
            $results = $this->multiFetch($queries, $fetches, $last, $transaction);
        } catch (goDBExceptionFetch $e) {
            /* Ошибка разбора - требуется вытащить все оставшиеся результаты и закомитить */
            while ($this->more_results()) {
                $this->next_result();
            }
            if ($transaction) {
                $this->transactionCommit();
            }
            throw $e;
        }
        if ($transaction) {
            $this->transactionCommit();
        }
        return $results;
    }

    /**
     * Создание подготовленного выражения
     *
     * @override mysqli::prepare()
     *
     * @link http://pyha.ru/go/godb/prepare/
     * @link http://ru.php.net/manual/en/mysqli.prepare.php
     *
     * @param string $query
     *        шаблон подготовленного запроса
     * @param bool $godb
     *        использовать godb-стиль (иначе - mysqli)
     * @return goDBPrepare
     *         или mysqli_stmt
     */
    public function prepare($query, $godb = false) {
        if (!$godb) {
            return parent::prepare($query);
        }
        return (new goDBPrepare($this, $query));
    }

    /**
     * Создание и выполнение подготовленного выражения
     *
     * @link http://pyha.ru/go/godb/prepare/
     *
     * @param string $query
     *        шаблон запроса или имя именованного выражения
     * @param array $data
     *        входные данные
     * @param string $fetch
     *        формат разбора
     */
    public function prepareExecute($query, $data, $fetch, $cache = true) {
        if (!$cache) {
            if (empty($query)) {
                return false;
            }
            if ($query[0] == '#') {
                throw new goDBExceptionPrepareNamed('Cannot create prepare by one name');
            }
            $prepare = new goDBPrepare($this, $query);
            $result  = $prepare->execute($data, $fetch);
            $prepare->close();
            return $result;
        }
        $prepare = $this->getPrepare($query, true);
        if (!$prepare) {
            throw new goDBExceptionPrepareNamed('Prepare "'.$query.'" not found');
        }
        if (is_string($cache)) {
            if ($cache[0] != '#') {
                $cache = '#'.$cache;
            }
            $this->cachePrepare[$cache] = $prepare;
        }
        return $prepare->execute($data, $fetch);
    }

    /**
     * Создание именованного подготовленного выражения
     *
     * @param string $name
     *        имя выражения
     * @param string $query
     *        шаблон выражения
     * @return goDBPrepare

     *         созданное подготовленное выражение
     */
    public function prepareNamed($name, $query) {
        if (empty($name)) {
            return false;
        }
        if ($name[0] != '#') {
            $name = '#'.$name;
        }
        $prepare = new goDBPrepare($this, $query, true);
        $this->cachePrepare[$name]  = $prepare;
        $this->cachePrepare[$query] = $prepare;
        return $prepare;
    }

    /**
     * Чтение подготовленного выражения из кэша
     *
     * @param string $query
     *        шаблон выражения или имя для именованного
     * @param bool $create [optional]
     *        создавать, если в кэше нет
     * @return goDBPrepare
     */
    public function getPrepare($query, $create = false) {
        if (empty($query)) {
            return false;
        }
        if (isset($this->cachePrepare[$query])) {
            return $this->cachePrepare[$query];
        }
        if ($query[0] == '#') {
            if ($create) {
                throw new goDBExceptionPrepareNamed('Cannot create prepare by one name');
            }
            return false;
        }
        $name = '#'.$query;
        if (isset($this->cachePrepare[$name])) {
            return $this->cachePrepare[$name];
        }
        if (!$create) {
            return false;
        }
        $prepare = new goDBPrepare($this, $query);
        $this->cachePrepare[$query] = $prepare;
        return $prepare;
    }

    /**
     * Получить объект ссылку
     *
     * @link http://pyha.ru/go/godb/godblink/
     *
     * @return goDBI
     */
    public function getLinkObject() {
        return (new goDBLink($this));
    }

    /**
     * Формирование запроса
     *
     * @link http://pyha.ru/go/godb/query/#ph-list
     *
     * @throws goDBExceptionData
     *         ошибочный шаблон или несответствие ему входных данных
     *
     * @param string $pattern
     *        строка-шаблон с плейсхолдерами
     * @param array $data
     *        массив входных данных
     * @param string $prefix [optional]
     *        префикс таблиц
     */
    public function makeQuery($pattern, $data, $prefix = null) {
        $prefix = ($prefix === null) ? $this->tablePrefix : $prefix;
		$query  = preg_replace('~{(.*?)}~', '`'.preg_quote($prefix).'\1`', $pattern);
		if (!$data) {
			return $query;
		}
        $this->_prefix  = $prefix;
        $this->_data    = $data;
        $this->_dataCnt = 0;
        $this->_phType  = null;
        $query = preg_replace_callback('~\?([a-z\?-]+)?(:([a-z0-9_-]*))?;?~i', Array($this, '_makeQuery'), $query);
        if (($this->_phType == 'r') && ($this->_dataCnt < count($data))) {
            throw new goDBExceptionDataMuch('It is too much data');
        }
        return $query;
    }

    /**
     * Разбор результата в нужном формате
     *
     * @link http://pyha.ru/go/godb/fetch/
     *
     * @throws goDBExceptionFetch
     *         неизвестный или неожиданный формат представления
     *
     * @param mysqli_result $result
     *        результат запроса
     * @param string $fetch
     *        требуемый формат представления
     * @return mixed
     *         результат в требуемом формате
     */
    public function fetch($result, $fetch) {
        $fetch   = explode(':', $fetch, 2);
        $options = isset($fetch[1]) ? $fetch[1] : '';
        $fetch   = strtolower($fetch[0]);
        $this->isiterator = false;
        switch ($fetch) {
            case null:
            case 'no':
                return $result;
            case 'true':
                return true;
            case 'id':
                return $this->insert_id;
            case 'ar':
                return $this->affected_rows;
        }
        if (!is_object($result)) {
            $this->checkFetch($fetch);
            throw new goDBExceptionFetchUnexpected($fetch);
        }
        switch ($fetch) {
            case 'assoc':
                $return = array();
                while ($row = $result->fetch_assoc()) {
                    $return[] = $row;
                }
                return $return;
            case 'row':
                $return = array();
                while ($row = $result->fetch_row()) {
                    $return[] = $row;
                }
                return $return;
            case 'col':
                $return = array();
                while ($row = $result->fetch_row()) {
                    $return[] = $row[0];
                }
                return $return;
            case 'object':
                $return = array();
                while ($row = $result->fetch_object()) {
                    $return[] = $row;
                }
                return $return;
            case 'vars':
                $return = array();
                while ($row = $result->fetch_row()) {
                    $return[$row[0]] = isset($row[1]) ? $row[1] : $row[0];
                }
                return $return;
            case 'kassoc':
                $return = array();
                $key    = $options;
                while ($row = $result->fetch_assoc()) {
                    if (!$key) {
                        reset($row);
                        $key = key($row);
                    }
                    $return[$row[$key]] = $row;
                }
                return $return;
            case 'iassoc':
                $this->isiterator = true;
                return new goDBResultAssoc($result);
            case 'irow':
                $this->isiterator = true;
                return new goDBResultRow($result);
            case 'icol':
                $this->isiterator = true;
                return new goDBResultCol($result);
            case 'iobject':
                $this->isiterator = true;
                return new goDBResultObject($result);
        }

        $num = $result->num_rows;
        if ($fetch == 'num') {
            return $num;
        }
        if ($num == 0) {
            $this->checkFetch($fetch, 'one');
            return false;
        }

        switch ($fetch) {
            case 'rowassoc':
                return $result->fetch_assoc();
            case 'rowrow':
                return $result->fetch_row();
            case 'rowobject':
                return $result->fetch_object();
            case 'el':
                $r = $result->fetch_row();
                return $r[0];
            case 'bool':
                $r = $result->fetch_row();
                return (bool)$r[0];
        }

        throw new goDBExceptionFetchUnknown($fetch);
    }

    /**
     * Установка префикса таблиц
     *
     * @link http://pyha.ru/go/godb/etc/
     *
     * @param string $prefix
     */
    public function setPrefix($prefix) {
        $this->tablePrefix = $prefix;
        return true;
    }

    /**
     * Получить текущий префикс
     *
     * @return string
     */
    public function getPrefix() {
        return $this->tablePrefix;
    }

    /**
     * Установить значение отладки
     *
     * @link http://pyha.ru/go/godb/etc/
     *
     * @param mixed $debug
     *        true:     вывод в поток
     *        false:    отключение отладки
     *        callback: вызов указанной функции
     */
    public function setDebug($debug = true) {
        $this->queryDebug = $debug;
        return true;
    }

    /**
     * Получить значение отладки
     *
     * @return mixed
     */
    public function getDebug() {
        return $this->queryDebug;
    }

    /**
     * Декорирование query()
     *
     * @link http://pyha.ru/go/godb/etc/
     *
     * @param callback $wrapper
     *        функция-декоратор
     */
    public function queryDecorated($wrapper) {
    	$this->queryWrapper = $wrapper;
    	return true;
    }

    /**
     * Получить декоратор запроса
     *
     * @return callback
     */
    public function getQueryDecorator() {
        return $this->queryWrapper;
    }

    /**
     * Количество записей в таблице, удволетворяющих условию
     *
     * @param string $table
     *        имя таблицы (используется префикс)
     * @param string $where [optional]
     *        условие WHERE (с плейсхолдерами). Нет - вся таблица.
     * @param array $data [optional]
     *        данные для WHERE
     * @return int
     *         количество записей удовлетворяющих условию
     */
    public function countRows($table, $where = null, $data = null) {
    	$where   = $where ? ('WHERE '.$where) : '';
    	$pattern = 'SELECT COUNT(*) FROM {'.$table.'} '.$where;
    	return $this->query($pattern, $data, 'el');
    }

  /*** STATIC: ***/

    /**
     * Имя по умолчанию в пространстве имён
     * @const string
     */
    const baseName = 'base';

    /**
     * Создание базы и сохранение в пространстве имен
     *
     * Возможно указание как всех аргументов метода,
     * так и указание единственного - конфигурационного массива
     *
     * @link http://pyha.ru/go/godb/namespace/
     *
     * @throws goDBExceptionDBAlready
     *         заданное имя уже существует
     * @throws goDBExceptionConnect
     *         ошибка подключения, если не используется отложенное подключение
     *
     * @param mixed $host
     *        хост - возможно указание порта через ":"
     *        либо конфигурационный массив
     * @param string $username [optional]
     *        имя пользователя базы
     * @param string $passwd [optional]
     *        пароль пользователя базы
     * @param string $dbname [optional]
     *        имя базы данных
     * @param string $name [optional]
     *        наименование данной базы в пространстве имён
     * @param bool $post [optional]
     *        использовать ли отложенное подключение
     * @return mixed
     *         объект базы данные если не используется отложенное подключение
     */
    public static function makeDB($host, $username = null, $passwd = null, $dbname = null, $name = null, $post = false) {
    	if (is_array($host)) {
    		$name = isset($host['name']) ? $host['name'] : self::baseName;
            if (isset($host['link'])) {
                self::assocDB($name, $host['link']);
                return true;
            }
    		$post = isset($host['postmake']) ? $host['postmake'] : false;
    	} elseif (!$name) {
            $name = self::baseName;
        }
        if (isset(self::$dbList[$name])) {
            throw new goDBExceptionDBAlready($name);
        }
        if (!$post) {
            self::$dbList[$name] = new self($host, $username, $passwd, $dbname);
        } else {
            self::$dbList[$name] = Array($host, $username, $passwd, $dbname);
        }
        return self::$dbList[$name];
    }

    /**
     * Сохранить базу в пространстве имен
     *
     * @throws goDBExceptionAlready
     *         имя занято
     *
     * @param goDB $db
     * @param string $name [optional]
     */
    public static function setDB(goDB $db, $name = self::baseName) {
        if (isset(self::$dbList[$name])) {
            throw new goDBExceptionDBAlready($name);
        }
        self::$dbList[$name] = $db;
        return true;
    }

    /**
     * Ассоциация с базой
     *
     * @throws goDBExceptionDBAlready
     *         имя новой занято
     * @throws goDBExceptionDBNotFound
     *         целевая база отсутствует
     *
     * @param string $one
     *        новая база
     * @param string $two
     *        та, с которой ассоциируется
     */
    public static function assocDB($one, $two) {
    	if (isset(self::$dbList[$one])) {
    		throw new goDBExceptionDBAlready($one);
    	}
    	if (!isset(self::$dbList[$two])) {
    		throw new goDBExceptionDBNotFound($two);
    	}
    	self::$dbList[$one] = $two;
    	return true;
    }

    /**
     * Получить базу из пространства имен
     *
     * @throws goDBExceptionDBNotFound
     *         нет базы с таким именем
     * @throws goDBExceptionConnect
     *         может произойти ошибка при отложенном подключении
     *
     * @param string $name
     * @return goDB
     */
    public static function getDB($name = self::baseName) {
        if (!isset(self::$dbList[$name])) {
            throw new goDBExceptionDBNotFound($name);
        }
        if (is_array(self::$dbList[$name])) {
            $prm = self::$dbList[$name];
            self::$dbList[$name] = new self($prm[0], $prm[1], $prm[2], $prm[3]);
        } elseif (!is_object(self::$dbList[$name])) {
        	self::$dbList[$name] = self::getDB(self::$dbList[$name]);
        }
        return self::$dbList[$name];
    }

    /**
     * Делегирование запроса к нужному объекту БД из пространства имён
     *
     * @throws goDBException
     *         - нет такой базы
     *         - ошибка отложенного подключения
     *         - ошибки шаблона и данных
     *         - ошиочный формат разбора
     *         - ошибочный запрос
     *
     * @param string $pattern [optional]
     * @param array  $data [optional]
     * @param string $fetch [optional]
     * @param string $prefix [optional]
     * @param string $name [optional]
     * @return mixed
     */
    public static function queryDB($pattern, $data = null, $fetch = null, $prefix = null, $name = self::baseName) {
        return self::getDB($name)->query($pattern, $data, $fetch, $prefix);
    }

	/**
	 * Получить количество запросов через данный класс
	 *
	 * @return int
	 */
    public static function getQQuery() {
    	return self::$qQuery;
    }

  /*** PRIVATE: ***/

    /**
     * Вспомагательная функция для формирования запроса
     *
     * @param array $matches
     * @return string
     */
    private function _makeQuery($matches) {
        if (!isset($matches[1])) {
            /* Простой регулярный (не именованный) плейсхолдер "?" */
            $placeholder = '';
            $type        = 'r';
        } else {
            /* Плейсхолдер с указанием типа и (или) именованный */
            $placeholder = strtolower($matches[1]);
            if ($placeholder == '?') {
                return '?';
            }
            if (isset($matches[2])) {
                /* Именованный плейсхолдер */
                if (isset($matches[3])) {
                    $type = 'n';
                    $name = $matches[3];
                    if (empty($name)) {
                        throw new goDBExceptionDataPlaceholder($matches[0]);
                    }
                } else {
                    /* ":" поставлена, а имя не указано */
                    throw new goDBExceptionDataPlaceholder($matches[0]);
                }
            } else {
                /* Регулярный плейсхолдер */
                $type = 'r';
            }
        }
        if ($type == 'r') {
            /* Для регулярного плейсхолдера индекс - очередной номер */
            $name = $this->_dataCnt;
            $this->_dataCnt++;
        }
        if (!$this->_phType) {
            /* Тип плейсхолдеров ещё не определён */
            $this->_phType = $type;
        } elseif ($this->_phType != $type) {
            /* Неоднородные плейсхолдеры в одном запроса */
            throw new goDBExceptionDataMixed('regularly and named placeholder in a query');
        }
        if (!array_key_exists($name, $this->_data)) {
            /* Нет такого индекса */
            throw new goDBExceptionDataNotEnough('It is not enough data');
        }
        $value = $this->_data[$name];

        switch ($placeholder) {
            case '':
            case 'string':
                return '"'.$this->real_escape_string($value).'"';
            case 'n':
            case 'null':
                return is_null($value) ? 'NULL' : '"'.$this->real_escape_string($value).'"';
            case 'i':
            case 'int':
                return (0 + $value);
            case 'bool':
                return $value ? '"1"' : '"0"';
            case 'in':
            case 'ni':
            case 'int-null':
                return is_null($value) ? 'NULL' : (0 + $value);
            case 'l':
            case 'list':
            case 'a':
                foreach ($value as &$e) {
                    $e = is_null($e) ? 'NULL' : '"'.$this->real_escape_string($e).'"';
                }
                return implode(',', $value);
            case 'li':
            case 'list-int':
            case 'ai':
            case 'ia':
                foreach ($value as &$e) {
                    $e = is_null($e) ? 'NULL' : (0 + $e);
                }
                return implode(',', $value);
            case 's':
            case 'set':
                $set = array();
                foreach ($value as $col => $val) {
                    $val   = is_null($val) ? 'NULL' : '"'.$this->real_escape_string($val).'"';
                    $set[] = '`'.$col.'`='.$val;
                }
                return implode(',', $set);
            case 'v':
            case 'values':
                $valueses = array();
                foreach ($value as $vs) {
                    $values = array();
                    foreach ($vs as $v) {
                        $values[] = is_null($v) ? 'NULL' : '"'.$this->real_escape_string($v).'"';
                    }
                    $valueses[] = '('.implode(',', $values).')';
                }
                return implode(',', $valueses);
            case 'e':
            case 'escape':
                return $this->real_escape_string($value);
            case 'q':
            case 'query':
                return $value;
            case 't':
            case 'table':
                return '`'.$this->_prefix.$value.'`';
            case 'c':
            case 'col':
                if (is_array($value)) {
                    return '`'.$this->_prefix.$value[0].'`.`'.$value[1].'`';
                }
                return '`'.$value.'`';
            default:
                throw new goDBExceptionDataPlaceholder($matches[0]);
        }
    }

    /**
     * Проверка формата разбора
     *
     * @throws goDBExceptionFetchUnknown
     *         неизвестный формат
     *
     * @param string $fetch
     *        формат разбора
     * @param string $groups [optional]
     *        в какой группе искать
     *        не указана - во всех
     */
    private function checkFetch($fetch, $group = null) {
        if ($group) {
            if (in_array($fetch, self::$listFetchs[$group])) {
                return true;
            }
        } else {
            foreach (self::$listFetchs as $fetchs) {
                if (in_array($fetch, $fetchs)) {
                    return true;
                }
            }
        }
        throw new goDBExceptionFetchUnknown($fetch);
    }

    /**
     * Приведение различных форматов multiQuery к одному
     *
     * @param mixed $patterns
     * @param mixed $datas
     * @param mixed $fetches
     * @return array
     *     "queries": массив запросов виде (pattern, data, fetch)
     *     "last":    last-fetch если указан
     */
    private function argsMultiQuery($patterns, $datas, $fetches) {
        $queries = array();
        if (is_array($patterns)) {
            if (!isset($patterns[0])) {
                return false;
            }
            if (is_array($patterns[0])) {
                $queries = $patterns;
            } else {
                $countP = count($patterns);
                $countD = count($datas);
                if ($countP != $countD) {
                    throw new goDBExceptionMulti('multi patterns != datas');
                }
                for ($i = 0; $i < $countP; $i++) {
                    $queries[] = array($patterns[$i], $datas[$i]);
                }
            }
        } else {
            foreach ($datas as $data) {
                $queries[] = array($patterns, $data);
            }
        }
        $last = null;
        if ($fetches) {
            if (is_array($fetches)) {
                $countQ = count($queries);
                $countF = count($fetches);
                if ($countQ != $countF) {
                    throw new goDBExceptionMulti('multi queries != fetches');
                }
                for ($i = 0; $i < $countQ; $i++) {
                    $queries[$i][2] = $fetches[$i];
                }
            } else {
                $f = explode(':', $fetches, 2);
                if (($f[0] == 'last') && (isset($f[1]))) {
                    $last = $f[1];
                } else {
                    foreach ($queries as &$q) {
                        $q[2] = $fetches;
                    }
                }
            }
        } else {
            foreach ($queries as &$q) {
                $q[2] = isset($q[2]) ? $q[2] : 'true';
            }
        }
        return array(
            'queries' => $queries,
            'last'    => $last,
        );
    }

    /**
     * Разбор результатов мультизапроса
     *
     * @throws goDBExceptionFetch
     *
     * @param array $queries
     * @param mixed $fetches
     * @param bool $last
     * @param bool $transaction
     * @return array
     */
    private function multiFetch($queries, $fetches, $last, $transaction) {
        $results = array();
        $notlast = count($queries);
        foreach ($queries as $q) {
            $notlast--;
            $result = $this->store_result();
            if ($this->errno) {
                if ($transaction) {
                    $this->transactionRollback();
                }
                throw new goDBExceptionQuery($q[0], $this->errno, $this->error);
            }
            if (!$last) {
                $fetch = $q[2];
                $r = $this->fetch($result, $fetch);
                $results[] = $r;
                if (is_object($result) && ($result !== $r)) {
                    $result->free();
                }
            } elseif (!$notlast) {
                $r = $this->fetch($result, $last);
                $results = $r;
                if (is_object($result) && ($result !== $r)) {
                    $result->free();
                }
            } else {
                if (is_object($result)) {
                    $result->free();
                }
            }
            if ($this->more_results()) {
                $this->next_result();
            } elseif ($notlast) {
                if ($transaction) {
                    $this->transactionRollback();
                }
                throw new goDBExceptionMulti('multi results < queires');
            }
        }
        if ($this->more_results()) {
            if ($transaction) {
                $this->transactionRollback();
            }
            throw new goDBExceptionMulti('multi results > queires');
        }
        return $results;
    }

    /**
     * Вывод отладочной информации
     *
     * @param string $message
     * @param floar $duration [optional]
     */
    private function toDebug($message, $duration = null) {
        if (!$this->queryDebug) {
            return false;
        }
       	if ($this->queryDebug === true) {
           	echo '<pre>'.htmlspecialchars($message).'</pre>';
       	} else {
            $args = func_get_args();
       		call_user_func_array($this->queryDebug, $args);
       	}
        return true;
    }

    /**
     * Деструктор объекта goDB
     * Чистка кэшей и т.п.
     */
    public function __destruct() {
        foreach ($this->cachePrepare as $p) {
            $p->close();
        }
        $this->cachePrepare = null;
    }

  /*** VARS: ***/

   /**
     * Префикс таблиц по умолчанию
     *
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * Разрешение отладки
     *
     * @var mixed
     */
    protected $queryDebug = false;

    /**
     * Список баз данных
     *
     * @var array
     */
    protected static $dbList = Array();

    /**
     * Количество запросов через класс
     *
     * @var int
     */
    protected static $qQuery = 0;

    /**
     * Текущий уровень вложенности транзакций
     *
     * @var int
     */
    protected $transactionLevel = 0;

    /**
     * Сохранённое значение автокоммита при старте транзакции
     *
     * @var bool
     */
    protected $transactionACStored;

    /**
     * Пометка текущей транзакции, как ошибочной при тихом откате
     *
     * @var bool
     */
    protected $transactionFailed = false;

    /**
     * Кэш подготовленных выражений
     *
     * Запросы хранятся как "query" => goDBPrepare
     * Именованные как "#name" => goDBPrepare
     *
     * @var array
     */
    protected $cachePrepare = array();

    /**
     * Список всех форматов представления результата по группам
     * @var array
     */
    private static $listFetchs = array(
        /* Возвращающие множество записей */
        'many' => array(
            'assoc', 'row', 'col', 'object', 'kassoc',
            'iassoc', 'irow', 'icol', 'iobject',
            'vars', 'num',
        ),
        /* Возвращаюшие одну запись */
        'one' => array(
            'rowassoc', 'rowrow', 'rowobject', 'el', 'bool',
        ),
        /* Другие */
        'other' => array(
            'no', 'id', 'ar',
        ),
    );

    /**
     * Декоратор query()
     *
     * @var callback
     */
    protected $queryWrapper;

    /* Вспомагательная фигня */
    private $_data;
    private $_dataCnt;
    private $_phType;
    private $_prefix;

    private $isiterator;
}


/****************************************
 *
 * Иерархия исключений при работе с библиотекой
 *
 ****************************************/

interface goDBException {}

abstract class goDBRuntimeException extends RuntimeException implements goDBException {

    final public static function truncateTrace($trace, $file) {
        $litem = null;
        foreach (array_reverse($trace) as $item) {
            if (isset($item['file']) && ($item['file'] === __FILE__)) {
                if (!$litem) {
                    return null;
                }
                return array(
                    'file' => isset($item['file']) ? $litem['file'] : null,
                    'line' => isset($item['line']) ? $litem['line'] : null,
                );
            }
            $litem = $item;
        }
        if ($file === __FILE__) {
            return array(
                'file' => isset($item['file']) ? $litem['file'] : null,
                'line' => isset($item['line']) ? $litem['line'] : null,
            );
        }
        return null;
    }

    public function __construct($message = '', $code = 0, $previous = null) {
        if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
            parent::__construct($message, $code, $previous);
        } else {
            parent::__construct($message, $code);
        }
        $file = self::truncateTrace($this->getTrace(), $this->file);
        if ($file) {
            $this->file = $file['file'];
            $this->line = $file['line'];
        }
    }
}
abstract class goDBLogicException extends LogicException implements goDBException {
    public function __construct($message = '', $code = 0, $previous = null) {
        if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
            parent::__construct($message, $code, $previous);
        } else {
            parent::__construct($message, $code);
        }
        $file = goDBRuntimeException::truncateTrace($this->getTrace(), $this->file);
        if ($file) {
            $this->file = $file['file'];
            $this->line = $file['line'];
        }
    }
}

/**
 * Ошибка подключения
 */
class goDBExceptionConnect extends goDBRuntimeException {}

/**
 * Ошибка в запросе
 */
class goDBExceptionQuery extends goDBLogicException
{
    public function __construct($query, $errno, $error) {
		$msg = 'DB Error. Query="'.$query.'" error = '.$errno.' "'.$error.'"';
		parent::__construct($msg, $errno);
        $this->query = $query;
        $this->errno = $errno;
        $this->error = $error;
    }

    public function query() {return $this->query;}
    public function errno() {return $this->errno;}
    public function error() {return $this->error;}

    public function __toString() {
        return htmlspecialchars($this->getMessage());
    }

    private $query, $errno, $error;
}

/**
 * Несоответствие количество плейсхолдеров и входных данных
 */
abstract class goDBExceptionData extends goDBLogicException {}

/**
 * Данных больше плейсхолдеров
 */
class goDBExceptionDataMuch extends goDBExceptionData {}

/**
 * Данных меньше плейсхолдеров
 */
class goDBExceptionDataNotEnough extends goDBExceptionData {}

/**
 * Неизвестный плейсхолдер
 */
class goDBExceptionDataPlaceholder extends goDBExceptionData {
    public function __construct($placeholder, $code = null) {
        $message = 'Unknown placeholder "'.$placeholder.'"';
        parent::__construct($message, $code);
    }
}

/**
 * В запросе используются именованные плейсхолдеры наравне с регулярными
 */
class goDBExceptionDataMixed extends goDBExceptionData {}

/**
 * Неверный fetch
 */
abstract class goDBExceptionFetch extends goDBLogicException {}

/**
 * Неверный fetch - неизвестный
 */
class goDBExceptionFetchUnknown extends goDBExceptionFetch {
    public function __construct($fetch, $code = null) {
        $message = 'Unknown fetch "'.$fetch.'"';
        parent::__construct($message, $code);
    }
}

/**
 * Неверный fetch - неожиданный (assoc после INSERT, например)
 */
class goDBExceptionFetchUnexpected extends goDBExceptionFetch {
    public function __construct($fetch, $code = null) {
        $message = 'Unexpected fetch "'.$fetch.'" for this query';
        parent::__construct($message, $code);
    }
}

/**
 * Исключения, связанные с транзакциями
 */
abstract class goDBExceptionTransaction extends goDBLogicException {}
class goDBExceptionTransactionRollback extends goDBExceptionTransaction {}

class goDBExceptionMulti extends goDBLogicException {}

abstract class goDBExceptionPrepare extends goDBLogicException {}
class goDBExceptionPrepareCreate extends goDBExceptionPrepare {}
class goDBExceptionPrepareNamed extends goDBExceptionPrepare {}


/**
 * Проблемы со списком баз данных в пространстве имен DB
 */
abstract class goDBExceptionDB extends goDBLogicException {}
class goDBExceptionDBAlready  extends goDBExceptionDB {}
class goDBExceptionDBNotFound extends goDBExceptionDB {}

/**
 * Результат выполнения запроса (итератор)
 *
 */
abstract class goDBResult implements Iterator, ArrayAccess, Countable {

    public function __construct($result) {
        $this->result  = $result;
        $this->numRows = $result->num_rows;
    }

    public function __destruct() {
        if (is_resource($this->result)) {
            $this->result->free();
        }
    }

    public function rewind() {
        $this->count = 0;
        return true;
    }

    public function current() {
        $this->result->data_seek($this->count);
        return $this->getEl();
    }

    public function key() {
        return $this->count;
    }

    public function next() {
        $this->count++;
        return $this->current();
    }

    public function valid() {
        if ($this->count >= $this->numRows) {
            return false;
        }
        return true;
    }

    public function count() {
        return $this->numRows;
    }

    public function get($num, $index = false)  {
        if ($num >= $this->numRows) {
            return null;
        }
        $this->result->data_seek($num);
        $r = $this->getEl();
        if ($index === false) {
            return $r;
        }
        if (!is_array($r)) {
            return null;
        }
        if (!isset($r[$index])) {
            return null;
        }
        return $r[$index];
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }
    public function offsetSet($offset, $value) {
        return false;
    }
    public function offsetExists($offset) {
        return (($offset >= 0) && ($offset < $this->numRows));
    }
    public function offsetUnset($offset) {
        return false;
    }


    abstract protected function getEl();

    protected $result, $numRows, $count = 0;
}

class goDBResultRow extends goDBResult
{
    protected function getEl() {
        return $this->result->fetch_row();
    }
}

class goDBResultAssoc extends goDBResult
{
    protected function getEl()  {
        return $this->result->fetch_assoc();
    }
}

class goDBResultCol extends goDBResult
{
    protected function getEl() {
        $r = $this->result->fetch_row();
        return $r[0];
    }
}

class goDBResultObject extends goDBResult
{
    protected function getEl() {
        return $this->result->fetch_object();
    }
}

/**
 * Класс подготовленных выражений
 *
 * @link http://pyha.ru/go/godb/prepare/
 */
class goDBPrepare
{

    /**
     * Конструктор
     *
     * @param goDB $db
     *        объект базы для которой создаётся
     * @param string $query
     *        шаблон запроса
     * @param bool $lazy [optional]
     *        использовать ли отложенное создание
     */
    public function __construct(goDB $db, $query, $lazy = false) {
        $this->db = $db;
        $this->makeQuery($query);
        if (!$lazy) {
            $this->makeSTMT();
        }
    }

    /**
     * Деструктор
     * Уничтожение выражения
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * Выполнение запроса
     *
     * @throws goDBExceptionQuery
     *
     * @param array $data
     *        набор входных данных
     * @param string $fetch
     *        формат представления результата
     * @return mixed
     *         результат в требуемом формате
     */
    public function execute($data = null, $fetch = null) {
        if ($this->closed) {
            return false;
        }
        if ($this->db->transactionFailed()) {
            return false;
        }
        if (!$this->stmt) {
            $this->makeSTMT();
        }
        $stmt = $this->stmt;
        $data = $data ? $data : array();
        $len = count($data);
        if ($len != $this->paramsCount) {
            if ($len < $this->paramsCount) {
                throw new goDBExceptionDataNotEnough();
            } else {
                throw new goDBExceptionDataMuch();
            }
        }
        if ($this->paramsCount > 0) {
            $params = array($this->types);
            foreach ($data as &$d) {
                $params[] = &$d;
            }
            call_user_func_array(array($stmt, 'bind_param'), $params);
        }
        $stmt->execute();
        if ($stmt->errno) {
            throw new goDBExceptionQuery('[PREPARE] '.$this->query, $stmt->errno, $stmt->error);
        }
        if ($this->fields) {
            $rR = array();
            $rA = array();
            $num = 0;
            foreach ($this->fields as $field) {
                $rA[$field] = null;
                $rR[$num] = &$rA[$field];
                $num++;
            }
            call_user_func_array(array($stmt, 'bind_result'), $rR);
            $result = $this->fetch($rR, $rA, $fetch);
            unset($rR);
            unset($rA);
        } else {
            $result = $this->fetch(true, true, $fetch);
        }
        $stmt->free_result();
        unset($params);
        unset($results);
        return $result;
    }

    /**
     * Выполнение запроса вызовом объекта
     *
     * @example $result = $prepare($data, $fetch);
     *
     * @uses PHP 5.3
     *
     * @param array $data
     * @param string $fetch
     * @return mixed
     */
    public function __invoke($data = null, $fetch = null) {
        return $this->execute($data, $fetch);
    }

    /**
     * Закрытие подключения
     */
    public function close() {
        if ($this->closed) {
            return false;
        }
        if ($this->stmt) {
            $this->stmt->close();
            $this->stmt = null;
        }
        $this->closed = true;
        return true;
    }

    /**
     * Получить объект mysqli_stmt для данного выражения
     *
     * @return mysqli_stmt
     */
    public function getSTMT() {
        if ($this->closed) {
            return false;
        }
        if (!$this->stmt) {
            $this->makeSTMT();
        }
        return $this->stmt;
    }

    /**
     * Получить строку с типами плейсхолдеров
     *
     * @return string
     */
    public function getTypes() {
        return $this->types;
    }

    /**
     * Закрыто ли уже выражение
     *
     * @return bool
     */
    public function isClosed() {
        return $this->closed;
    }

    /**
     * Получение шаблона запроса
     *
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Создание подготовленного выражения mysqli
     */
    private function makeSTMT() {
        $this->stmt = $this->db->prepare($this->query);
        if (!$this->stmt) {
            $message =
                'Error prepare "'.$this->query.'"; '.
                    '#'.$this->db->errno.
                    ': "'.$this->db->error.'"';
            throw new goDBExceptionPrepareCreate($message);
        }
        if ($this->stmt->param_count != $this->paramsCount) {
            $message = 'prepare "'.$this->query.'" error param_count';
            throw new goDBExceptionPrepareCreate($message);
        }
        $meta = $this->stmt->result_metadata();
        if ($meta) {
            $fields = $meta->fetch_fields();
            $this->fields = array();
            foreach ($fields as $field) {
                $this->fields[] = $field->name;
            }
            $meta->free();
        } else {
            $this->fields = false;
        }
        return true;
    }

    /**
     * Разбор шаблона goDB на шаблон mysqli и строку типов
     */
    private function makeQuery($pattern) {
        $reg = '~\?([idsb])?;?~';
        if (!preg_match_all($reg, $pattern, $matches, PREG_SET_ORDER)) {
            $this->query = $pattern;
            $this->types = '';
            $this->paramsCount = 0;
            return true;
        }
        $this->paramsCount = count($matches);
        $t = array();
        foreach ($matches as $match) {
            if (isset($match[1])) {
                $t[] = $match[1];
            } else {
                $t[] = 's';
            }
        }
        $this->types = implode('', $t);
        if ($this->paramsCount > 0) {
            $pattern = preg_replace($reg, '?', $pattern);
        }
        $this->query = $pattern;
        return true;
    }

    /**
     * Разбор результата
     * @param array $row
     * @param array $assoc
     * @param string $fetch
     * @return mixed
     */
    private function fetch($row, $assoc, $fetch) {
        $fetch   = explode(':', $fetch, 2);
        $options = isset($fetch[1]) ? $fetch[1] : '';
        $fetch   = strtolower($fetch[0]);
        $stmt    = $this->stmt;
        switch ($fetch) {
            case null:
                return true;
            case 'no':
                throw new goDBExceptionFetchUnexpected($fetch);
            case 'true':
                return true;
            case 'id':
                return $stmt->insert_id;
            case 'ar':
                return $stmt->affected_rows;
        }
        if (!is_array($row)) {
            throw new goDBExceptionFetchUnexpected($fetch);
        }
        switch ($fetch) {
            case 'assoc':
            case 'iassoc':
                $result = array();
                while ($stmt->fetch()) {
                    $result[] = $this->aCopy($assoc);
                }
                return $result;
            case 'row':
            case 'irow':
                $result = array();
                while ($stmt->fetch()) {
                    $result[] = $this->aCopy($row);
                }
                return $result;
            case 'col':
            case 'icol':
                $result = array();
                while ($stmt->fetch()) {
                    $result[] = $row[0];
                }
                return $result;
            case 'object':
            case 'iobject':
                $result = array();
                while ($stmt->fetch()) {
                    $result[] = (object)$this->aCopy($assoc);
                }
                return $result;
            case 'vars':
                $result = array();
                while ($stmt->fetch()) {
                    $return[$row[0]] = isset($row[1]) ? $row[1] : $row[0];
                }
                return $return;
            case 'kassoc':
                $result = array();
                $key    = $options;
                while ($stmt->fetch()) {
                    if (!$key) {
                        $key = key($assoc);
                    }
                    $result[$assoc[$key]] = $this->aCopy($assoc);
                }
                return $result;
        }

        if ($fetch == 'num') {
            $num = 0;
            while ($stmt->fetch()) {
                $num++;
            }
            return $num;
        }

        if (!$stmt->fetch()) {
            return false;
        }

        switch ($fetch) {
            case 'rowassoc':
                return $this->aCopy($assoc);
            case 'rowrow':
                return $this->aCopy($row);
            case 'rowobject':
                return (object)$this->aCopy($assoc);
            case 'el':
                return $row[0];
            case 'bool':
                return (bool)$row[0];
        }

        throw new goDBExceptionFetchUnknown($fetch);
    }

    private function aCopy($A) {
        $r = array();
        foreach ($A as $k => $v) {
            $r[$k] = $v;
        }
        return $r;
    }

    /**
     * Связанный объект базы
     * @var goDB
     */
    private $db;

    /**
     * Шаблон запроса
     * @var string
     */
    private $query;

    /**
     * Строка типов
     * @var string
     */
    private $types;

    /**
     * Количество входных параметров
     * @var int
     */
    private $paramsCount;

    /**
     * Подготовленное выражение mysqli
     * @var mysqli_stmt
     */
    private $stmt;

    /**
     * Закрыто ли подключение
     * @var bool
     */
    private $closed = false;

    /**
     * Имена возвращаемых полей
     * @var array
     */
    private $fields;
}

interface goDBI {}

/**
 * Объект-ссылка.
 * Разделяет тоже подключение, но может иметь другие настройки.
 */
class goDBLink implements goDBI {

    /**
     * Конструктор
     *
     * @param goDBI $db
     *        объект, на который нужно сделать ссылку
     */
    public function __construct(goDBI $db) {
        $this->db      = $db;
        $this->prefix  = $db->getPrefix();
        $this->debug   = $db->getDebug();
        $this->wrapper = $db->getQueryDecorator();
    }

    /**
     * Получить исходный объект
     * @return goDB
     */
    public function godbObject() {
        return $this->db;
    }

    public function __get($name) {
        return $this->db->$name;
    }
    public function __set($name, $value) {
        $this->db->$name = $value;
    }
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->db, $name), $arguments);
    }

    /**
     * @override
     * @param string $pattern
     * @param array $data [optional]
     * @param string $fetch [optional]
     * @param string $prefix [optional]
     */
    public function query($pattern, $data = null, $fetch = null, $prefix = null) {
        $prefix  = $prefix ? $prefix : $this->prefix;
        $debug   = $this->db->getDebug();
        $wrapper = $this->db->getQueryDecorator();
        $this->db->setDebug($this->debug);
        $this->db->queryDecorated($this->wrapper);
        try {
            $result  = $this->db->query($pattern, $data, $fetch, $prefix);
        } catch (Exception $e) {
            $this->db->setDebug($debug);
            $this->db->queryDecorated($wrapper);
            throw $e;
        }
        $this->db->setDebug($debug);
        $this->db->queryDecorated($wrapper);
        return $result;
    }

    public function __invoke($pattern, $data = null, $fetch = null, $prefix = null) {
        return $this->query($pattern, $data, $fetch, $prefix);
    }

    /**
     * @override
     * @param string $pattern
     * @param array $data [optional]
     * @param string $prefix [optional]
     */
    public function makeQuery($pattern, $data = null, $prefix = null) {
        $prefix = $prefix ? $prefix : $this->prefix;
        return $this->db->makeQuery($pattern, $data, $prefix);
    }



    public function setPrefix($prefix) {
        $this->prefix = $prefix;
        return true;
    }
    public function getPrefix() {
        return $this->prefix;
    }
    public function setDebug($debug = true) {
        $this->debug = $debug;
        return true;
    }
    public function getDebug() {
        return $this->debug;
    }
    public function queryDecorated($wrapper) {
    	$this->wrapper = $wrapper;
    	return true;
    }
    public function getQueryDecorator() {
        return $this->wrapper;
    }

    /**
     * Исходный объект
     * @var goDB
     */
    private $db;

    private $prefix, $debug, $wrapper;
}
