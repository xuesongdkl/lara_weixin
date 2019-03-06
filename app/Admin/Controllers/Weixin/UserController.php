<?php

namespace App\Admin\Controllers\Weixin;

use App\Model\WxUserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use GuzzleHttp\Client;

class UserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WxUserModel);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid');
        $grid->add_time('Add time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->nickname('Nickname');
        $grid->sex('Sex')->display(function($sex){
            if($sex==1){
                return '男';
            }elseif($sex==2){
                return '女';
            }else{
                return "待定";
            }
        });
        $grid->headimgurl('Headimgurl')->display(function($img){
            return "<img width=100 src=".$img.">";
        });
        $grid->subscribe_time('Subscribe time');
        $grid->unionid('Unionid');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WxUserModel::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');
        $show->unionid('Unionid');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WxUserModel);

        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->number('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');
        $form->text('unionid', 'Unionid');

        return $form;
    }


   /*
    * 消息群发视图页面
    */
    public function sendMsgView(Content $content){
        return $content
            ->header('Create')
            ->description('description')
            ->body(view('admin.weixin.sendmsg'));
    }

    /*
     * 消息群发
     */
    public function sendMsg(){
//        echo "<pre>";print_r($_POST);echo "</pre>";
        //获取用户列表
        $users=WxUserModel::all()->toArray();
        $arr_openid=array_column($users,'openid');

        //发送
        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.WxUserModel::getAccessToken();
        $data=[
            'touser'        =>  $arr_openid,
            'msgtype'       =>  'text',
            'text'          =>  [
                'content'     =>   $_POST['msg']
            ]
        ];
        $client=new Client();
        $r = $client->request('POST', $url, [
            'body' => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $r->getBody();
    }
}
