<?php
$user = \App\Admin\Admin::user()->isAdministrator();
  $userid = \App\Admin\Admin::user();
  if($user){
    $newOrders = \App\Models\ShopOrder::where('status',1)->orderBy('id','desc');
    $totalNewOrders = $newOrders->count();
    $orders = $newOrders->limit(10)->get();
  }
  else
  {
    $newOrders = \App\Models\ShopOrderDetail::where('adminid',$userid->id)->orderBy('id','desc')->groupBy('order_id');    
    $orders = $newOrders->limit(10)->get();
     $totalNewOrders = count($orders);
     
  }

  
?>

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">{{$totalNewOrders}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header"> {{ trans('admin.menu_notice.new_order',['total'=>$totalNewOrders]) }}</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  @foreach ($orders as $order)
                  <?php if($user) {
                  ?>
                    <li>
                      <a href="{{route('admin_order.detail',['id'=>$order->id])}}">
                        <i class="fa fa-shopping-cart text-green"></i> #{{$order->id}} - {{ trans('admin.menu_notice.date') }}: {{$order->created_at}}
                      </a>
                    </li> 
                    <?php } else { ?> 
                      <li>
                      <a href="{{route('admin_vorder.detail',['id'=>$order->order_id])}}">
                        <i class="fa fa-shopping-cart text-green"></i> #{{$order->order_id}} - {{ trans('admin.menu_notice.date') }}: {{$order->created_at}}
                      </a>
                    </li> 
                    <?php } ?>                    
                  @endforeach
                </ul>
              </li>
              <li class="footer"><a href="{{route('admin_order.index')}}?order_status=1">{{ trans('admin.menu_notice.view_all') }}</a></li>
            </ul>
          </li>
