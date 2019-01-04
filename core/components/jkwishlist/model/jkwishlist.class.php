<?php

class Jkwishlist
{
    /** @var modX $modx */
    public $modx;
    public $config;
    protected $wishlist;
    protected $ctx = 'web';

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/jkwishlist/';
        $assetsUrl = MODX_ASSETS_URL . 'components/jkwishlist/';
        $actionUrl = $this->modx->getOption('jkw.action_url', $config, $assetsUrl . 'action.php');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'actionUrl' => $actionUrl,
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'wishlist' => & $_SESSION['wishlist'],
        ], $config);
        $this->wishlist = &$this->config['wishlist'];
        $this->modx->addPackage('jkwishlist', $this->config['modelPath']);
        $this->modx->lexicon->load('jkwishlist:default');
    }

    public function initialize($ctx = 'web')
    {
        $this->ctx = $ctx;
        $js = 'jkwishlist.js';
        $this->modx->regClientScript($this->config['jsUrl'] . $js);
        $data = json_encode(array(
            'cssUrl' => $this->config['cssUrl'] . 'web/',
            'jsUrl' => $this->config['jsUrl'] . 'web/',
            'actionUrl' => $this->config['actionUrl'],
            'ctx' => $ctx,
            ), true);
        $this->modx->regClientStartupScript(
            '<script type="text/javascript">jkwConfig = ' . $data . ';</script>', true
        );

        return true;
    }
    public function add($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return $this->error('jkw_err_id');
        }
        $erdata=array('usercontext'=>$this->modx->user->hasSessionContext($this->ctx));
        if($this->modx->user->hasSessionContext($this->ctx)){
            $obj = $this->modx->getObject('JkwishlistItem',['user'=>$this->modx->user->get('id')]);
            if($obj){
                $prods = !empty($obj->products)?json_decode($obj->products,true) : array();
                if(!in_array($id,$prods)) $prods[] = $id;
                $obj->products = $prods;
                $obj->save();
                $this->wishlist = $prods;
            }
            else {
                $obj = $this->modx->newObject('JkwishlistItem');
                $obj->user = $this->modx->user->get('id');
                $obj->products = array($id);
                $obj->save();
                if(!in_array($id,$this->wishlist)) $this->wishlist[] = $id;
            }
         @session_write_close();
         return $this->success('ok',$this->wishlist);
         }
        else {
            if(!in_array($id,$this->wishlist)) $this->wishlist[] = $id;
            @session_write_close();
            return $this->error('ok',$erdata);
        }
    }
    public function remove($id)
    {
        $log = array();
        if (empty($id) || !is_numeric($id)) {
            return $this->error('jkw_err_id');
        }
            $obj = $this->modx->getObject('JkwishlistItem',['user'=>$this->modx->user->get('id')]);
            if($obj){
                $prods = !empty($obj->products)?json_decode($obj->products,true) : array();
                if(!in_array($id,$prods)) return $this->error('jkw_no_such_id');
                else unset($prods[array_search($id, $prods)]);
                $obj->products = $prods;
                $obj->save();
            }
            if(in_array($id,$this->wishlist)) unset($this->wishlist[array_search($id, $this->wishlist)]);
            @session_write_close();
            $log[] =$this->wishlist;
        return $this->success('ok',$log);
    }
    public function clear(){
        $obj = $this->modx->getObject('JkwishlistItem',['user'=>$this->modx->user->get('id')]);
        if($obj){
            $obj->remove();
        }
    }
    public function get()
    {


        $user_id = $this->modx->user->get('id');
        if(!$user_id){
          if(!empty($this->wishlist)) return array('products'=>$this->wishlist);
            else return false;
        }
        elseif ($this->modx->user->hasSessionContext($this->ctx)) {
            $pdo = $this->modx->getService('pdoFetch');
            if(!$pdo)  $obj = $pdo->getObject('JkwishlistItem',array('user'=>$user_id));
            else { $obj = $this->modx->getObject('JkwishlistItem',array('user'=>$user_id)); $obj=$obj->toArray();}
            return $obj;
        }



    }

    public function error($message = '', $data = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }

        public function success($message = '', $data = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }
    public function handleRequest($action, $data = array())
    {

//        $this->initialize();

        switch ($action) {
            case 'wishlist/add':
                $id= isset($_POST['id'])?$_POST['id'] : null;
                if($id){
                    $wishlist = $this->add($id);
                }
                $response = json_encode($wishlist);
                break;
            case 'wishlist/remove':
                $id= isset($_POST['id'])?$_POST['id'] : null;
                if($id){
                    $wishlist = $this->remove($id);
                }
                $response = json_encode($wishlist);

                break;

            default:

                $response = $this->error('error handle request');
        }

        return $response;
    }


}