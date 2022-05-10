<?php $currentURL = url()->current();
$baseURL= url('/');
$basePath=str_replace($baseURL, '', $currentURL);
$total_price = 0;
$discount_price = 0.00;
?>
@extends('layouts.frontend_layout')
@section('title','Order Details | Webqom Technologies')
@section('content')
@section('page_header','Services')


<!-- end page title -->
<div class="clearfix">
  <div class="page_title1 sty9">
    <div class="container">
      <h1>Orders</h1>
      <div class="pagenation">&nbsp;<a href="{{url('/')}}">Home</a> <i>/</i> <a href="{{url('/client_area_home')}}">Dashboard</a> <i>/</i> Orders <i>/</i> My Order History <i>/</i> Order Details</div>
    </div>
  </div>
</div>

<div class="clearfix"></div>
<div class="clearfix margin_bottom5"></div>

 <div class="one_full stcode_title9">
        <h1 class="caps"><strong>Order Details </strong>     </h1>
    </div>

<div class="clearfix"></div>


<div class="content_fullwidth">

    <div class="container">
      @include('layouts.frontend_menu_login')
        <div class="three_fourth_less last">
        
           <div class="three_fourth_less last">
        
           <div class="text-18px dark light">Below you can view your order details &amp; track your order.</div>
           <div class="clearfix margin_bottom1"></div>
          
                <div class="one_third_less">
                  <h4>Receipt #: </h4>
                    <div class="text-16px red light"><a href="{{ route('receipt', $orderDetails->id )}}" target="_blank">{{ $orderDetails->id }}</a></div>
                  <h4>Invoice #:</h4> 
                    <div class="text-16px red light"><a href="#" target="_blank">MY-{{ $orderDetails->transaction_id }}</a></div>
                    <div class="margin_bottom1"></div>
                    
                    
                  
                                       
                
                </div><!-- end one third less -->
             
                <div class="one_third_less">
                  <h4>Order Date: </h4>
                    <div class="text-16px dark light">{{ date("jS M Y", strtotime($orderDetails->created_at))}}</div>
                    <div class="margin_bottom1"></div>
                    <h4>Status:</h4>
                    <div class="text-16px">
                        @if($orderDetails->status == 'COMPLETED')
                            <span class="label label-success caps">Paid</span>
                        @elseif($orderDetails->status == 'INCOMPLETE')
                            <span class="label label-warning caps">Unpaid</span>
                        @else
                            <span class="label label-danger caps">Payment Failed</span>
                        @endif
                    </div>           
                
                
                </div><!-- end one third less -->
                
                <div class="one_third_less last">
                  <h4>Total: </h4>
                    <div class="text-16px light red">RM {{ number_format($orderDetails->total_amount, 2) }}</div>
                    <div class="margin_bottom1"></div>
                    <h4>Payment Method: </h4>
                    <div class="text-16px dark light">
                        @if($orderDetails->payment_method)
                          {{$orderDetails->payment_method->name}}
                        @else
                          {{"Not Specific"}}
                        @endif
                    </div>
                
                
                </div><!-- end one third less last -->
              </div>
              <div>
                <div class="clearfix divider_line7"></div>
                <div class="clearfix"></div>
                
                
                <h4>Your Order Details</h4>

                <div class="table-responsive">
                      <table class="table table-hover table-striped">
                        <thead>
                          <tr>
                            <th><span class="pull-left">#</span> <a href="#sort by #" class="pull-right white"><i class="fa fa-sort"></i></a></th>      
                            <th width="30%"><span class="pull-left">Services</span> <a href="#sort by services" class="pull-right white"><i class="fa fa-sort"></i></a></th>
                          <th><span class="pull-left">Cycle</span> <a href="#sort by cycle" class="pull-right white"><i class="fa fa-sort"></i></a></th>                  
                            <th><span class="pull-left">Qty</span></th>
                            <th style="text-align:center"><span class="">Price</span> <a href="#sort by price" class="pull-right white"><i class="fa fa-sort"></i></a></th>                                
                          </tr>
                        </thead>
                        <tbody>
                            <?php $main_price = 0;$domain_price = 0; ?>

                          @foreach($orderDetails->orderItems as  $key => $value) 
                            @if(!empty($value->plan) && !empty($value->services) && !empty($value->addons))
                            <?php $val = 3; ?>
                            @elseif(!empty($value->plan) && !empty($value->services) && empty($value->addons))
                            <?php $val = 2; ?>
                            @elseif(empty($value->plan) && !empty($value->addons) && !empty($value->addons))
                            <?php $val = 2;?>
                            @else
                            <?php $val = 1; ?>
                            @endif
                            @if(!empty($value->plan))
                            <?php
                                $main_price = number_format((float)($value->plan->price_monthly * 12)+$value->plan->setup_fee_one_time, 2, '.', '');
                                $domain_price = number_format((float)$value['price'] - (($value->plan->price_monthly * 12)+$value->plan->setup_fee_one_time), 2, '.', ''); ?>
                            
                                <?php $discount = App\Models\Promotion::get_discount($value['plan_id']);
                               if($discount != NULL){
                                 $discount = json_decode(json_encode($discount));
                                
                                if($discount->discount_by == 'amount'){
                                  $discount = $discount->discount;
                                }else{
                                  $discount = ( $value['price'] * $discount->discount / 100);
                                }
                               }else{
                                $discount = 0.00;
                               } ?>
                            @else

                              @php $main_price = $value->price; $domain_price = 0; $discount = 0.00; @endphp
                              
                            @endif
                            <?php 
                            if($value['price'] != ''){
                              $row_price = $value['price'];
                            }else{
                              $row_price = 0.00;
                            }
                            ?>
                            @if($value->type == 2)
                              <?php $text = 'Transfer'; ?>
                              @else
                              <?php $text = 'Registration'; ?>
                            @endif
                            <tr data-id="{{ isset($value['id']) ? $value['id'] : $value['services'] }}">
                              <td rowspan="{{ $val }}">{{ $key + 1 }} </td>
                              @if(!empty($value->plan))
                              <td>
                                    <b>Service Code: </b> <span class="sitecolor">{{!empty($value->plan->service_code) ? $value->plan->service_code : ''}}</span><br/>
                                    <b>Hosting Plan:</b> <span class="sitecolor caps">{{!empty($value->plan->plan_name) ? $value->plan->plan_name : ''}}</span><br/>
                                   @php
                                    $featured_plans = App\Models\PlanFeature::where('page', $value->plan->page)->where('status', 1)->get();
                                  @endphp
                                   @if(!empty($featured_plans) && count($featured_plans)>0 && count($value->plan)>0)
                                    <b>Server Specification:</b>
                                    <ul style="margin-bottom:1px">
                                    @foreach($featured_plans as $i)         
                                      @php
                                        $details = App\Models\PlanFeatureDetail::where('plan_feature_id', $i->id)->where('plan_id', $value->plan->id)->first();
                                      @endphp
                                      @if ($details)
                                      <li><i class="fa icon-arrow-right"></i>&nbsp;&nbsp;{{$i->title}}:
                                        <span data-sel="{{$i->title}}">{{ $details->description }}</span>
                                      </li>
                                      @endif
                                    @endforeach
                                    </ul>
                                    @endif
                                </td>
                                
                                <td>

                                  @if($main_price != $value['price'])
                                 
                                  <?php echo "1 year"; 
                                    $domain_year = $value['cycle'];
                                  ?> 
                                  @else
                                    {{ $value['cycle'] }} <?php if($value['cycle'] == 1) echo "year"; else echo "years"; ?> <br/>
                                    <?php $domain_year = ''; ?>
                                  @endif
                                </td>
                                <td>
                                  @if($main_price != $value['price']) 
                                    {{$value['qty']}} 
                                    <?php $domain_qty = $value['qty']; ?>
                                  @else
                                    {{$value['qty']}}
                                    <?php $domain_qty = ''; ?>
                                  @endif
                                </td>
                                <td>
                                  <?php 
                                    if($main_price != $value['price']){ ?>
                                      RM {{ $main_price }} 
                                      <?php $d_price = $domain_price; ?>
                                     
                                   <?php }else{ ?>
                                      RM {{ number_format(($value['price']?$value['price']:'0'),2)}} <br>
                                      <?php $d_price = ''; ?>
                                   <?php } ?>
                                </td>
                              </tr>

                                  @else
                                  <?php 
                                  $domain_year = $value['cycle'];
                                  $domain_qty = $value['qty'];
                                  $d_price =  number_format(($value['price']?$value['price']:'0'),2);
                                   ?>
                                 
                                   <tr>
                                  <td>
                              @endif
                             
                              <?php if(isset($value->addons) && $value->addons != "" && $value->addons != null){ ?>
                              
                              
                                   <?php $addons_vl = explode(',', $value->addons); ?>
                                    <b>Domain Addons:</b>
                                    <ul style="margin-bottom:1px">
                                      @foreach($addons_vl as $addon)
                                        @foreach($domain_pricings as $dprice)
                                          <?php 
                                          if($addon == $dprice->id){ 
                                              $row_price += $dprice->price;
                                          ?>
                                          <li><i class="fa icon-arrow-right"></i>{{$dprice->title}} (RM {{ number_format($dprice->price, 2) }})</li>
                                         <?php }
                                          ?>
                                        @endforeach
                                      @endforeach
                                    </ul>
                                  
                                </td>
                              <td><?php echo "&nbsp;&nbsp;";?></td>
                              <td>
                                
                                    <br>
                                    <ul style="margin-bottom:0"> 
                                    @foreach($addons_vl as $addon)
                                      @foreach($domain_pricings as $dprice)
                                      
                                        <?php if($addon == $dprice->id){ ?>
                                          <li>1</li>
                                        <?php } ?>
                                      @endforeach
                                    @endforeach
                                    </ul>
                              </td>
                              <td>
                                    <br>
                                    <ul style="margin-bottom:0"> 
                                    @foreach($addons_vl as $addon)
                                      @foreach($domain_pricings as $dprice)
                                        <?php if($addon == $dprice->id){ ?>
                                          <li>RM {{ number_format($dprice->price, 2)}}</li>
                                        <?php } ?>
                                      @endforeach
                                    @endforeach
                                    </ul>
                                  
                              </td>
                            </tr>
                              <?php } ?>
                              

                            <tr>
                              
                              <td><b>Domain {{ $text }}:</b> <span class="sitecolor">{{$value['services']}}</span></td>
                              <td>@if(!empty($domain_year))
                                 
                                  <?php echo $domain_year. ' Years'; ?>
                                  
                                  @endif</td>
                              <td>
                                <?php if(!empty($domain_qty)){ ?>
                                 {{ $domain_qty }}

                                 <?php } ?>
                              </td>
                              <td>
                                @if($value['type'] == 2)
                                  <a href="{{ route('price_list',$value['services']) }}" target="_blank">RM {{ number_format(($value['price']?$value['price']:'0'),2)}}</a> <br>
                                @else
                                  @if(!empty($d_price))
                                  RM {{ $d_price }}
                                  @endif
                                @endif
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                        </tbody>

                      </table>
                      <table class="table table-hover table-striped">
                        <thead>
                          <tr>
                            <th><span class="pull-left">#</span> <a href="#sort by #" class="pull-right white"><i class="fa fa-sort"></i></a></th>      
                            <th width="30%"><span class="pull-left">Services</span> <a href="#sort by services" class="pull-right white"><i class="fa fa-sort"></i></a></th>
                          <th><span class="pull-left">Cycle</span> <a href="#sort by cycle" class="pull-right white"><i class="fa fa-sort"></i></a></th>                  
                            <th><span class="pull-left">Qty</span></th>
                            <th style="text-align:center"><span class="">Price</span> <a href="#sort by price" class="pull-right white"><i class="fa fa-sort"></i></a></th>                                
                          </tr>
                        </thead>
                        <tbody>
                          <?php $main_price = 0;$domain_price = 0; ?>

                          @foreach($orderDetails->orderItems as  $key => $value) 
                            @if(!empty($value->plan) && !empty($value->services) && !empty($value->addons))
                            <?php $val = 3; ?>
                            @elseif(!empty($value->plan) && !empty($value->services) && empty($value->addons))
                            <?php $val = 2; ?>
                            @elseif(empty($value->plan) && !empty($value->addons) && !empty($value->addons))
                            <?php $val = 2;?>
                            @else
                            <?php $val = 1; ?>
                            @endif
                            @if(!empty($value->plan))
                            <?php
                                $main_price = number_format((float)($value->plan->price_monthly * 12)+$value->plan->setup_fee_one_time, 2, '.', '');
                                $domain_price = number_format((float)$value['price'] - (($value->plan->price_monthly * 12)+$value->plan->setup_fee_one_time), 2, '.', ''); ?>
                            
                                <?php $discount = App\Models\Promotion::get_discount($value['plan_id']);
                               if($discount != NULL){
                                 $discount = json_decode(json_encode($discount));
                                
                                if($discount->discount_by == 'amount'){
                                  $discount = $discount->discount;
                                }else{
                                  $discount = ( $value['price'] * $discount->discount / 100);
                                }
                               }else{
                                $discount = 0.00;
                               } ?>
                            @else

                              @php $main_price = $value->price; $domain_price = 0; $discount = 0.00; @endphp
                              
                            @endif
                            <?php 
                            if($value['price'] != ''){
                              $row_price = $value['price'];
                            }else{
                              $row_price = 0.00;
                            }
                            ?>
                            @if($value->type == 2)
                              <?php $text = 'Transfer'; ?>
                              @else
                              <?php $text = 'Registration'; ?>
                            @endif
                            <tr data-id="{{ isset($value['id']) ? $value['id'] : $value['services'] }}">
                              <td>{{ $key + 1 }}</td>
                              <td>
                                <div class="pull-left">
                                  <table style="">
                                  <!-- <b>Service Code: </b> <span class="sitecolor">DN</span><br/> -->
                                  <tr><td>
                                    <!-- <b>Service Code: </b> <span class="sitecolor">DN</span><br/> -->
                                  @if(!empty($value->plan))
                                    <b>Service Code: </b> <span class="sitecolor">{{!empty($value->plan->service_code) ? $value->plan->service_code : ''}}</span><br/>
                                    <b>Hosting Plan:</b> <span class="sitecolor caps">{{!empty($value->plan->plan_name) ? $value->plan->plan_name : ''}}</span><br/>
                                   @php
                                   $total_break=3;
                                    $featured_plans = App\Models\PlanFeature::where('page', $value->plan->page)->where('status', 1)->get();
                                  @endphp
                                   @if(!empty($featured_plans) && count($featured_plans)>0 && count($value->plan)>0)
                                    <b>Server Specification:</b>
                                    <ul style="margin-bottom:1px">
                                    @foreach($featured_plans as $i)         
                                      @php
                                        $details = App\Models\PlanFeatureDetail::where('plan_feature_id', $i->id)->where('plan_id', $value->plan->id)->first();
                                      @endphp
                                      @if ($details)
                                      <?php $total_break++; ?>
                                      <li><i class="fa icon-arrow-right"></i>&nbsp;&nbsp;{{$i->title}}:
                                        <span data-sel="{{$i->title}}">{{ $details->description }}</span>
                                      </li>
                                      @endif
                                    @endforeach
                                    </ul>
                                    @endif
                                  @endif
                                </td></tr>
                                    <?php if(isset($value->addons) && $value->addons != "" && $value->addons != null){
                                    $addons_vl = explode(',', $value->addons); ?>
                                    <tr><td>
                                    <b>Domain Addons:</b>

                                    <ul style="margin-bottom:0">
                                      <?php $d_ad = 1; ?>
                                    @foreach($addons_vl as $addon)
                                      @foreach($domain_pricings as $dprice)
                                        <?php 
                                        if($addon == $dprice->id){ 
                                            $row_price += $dprice->price;
                                            $d_ad++;
                                        ?>
                                        <li><i class="fa icon-arrow-right"></i>{{$dprice->title}} (RM {{ number_format($dprice->price, 2) }})</li>
                                       <?php }
                                        ?>
                                      @endforeach
                                    @endforeach
                                    </ul>
                                    </td></tr>
                                  <?php } ?>
                                  <tr><td>
                                  <b>Domain {{ $text }}:</b> <span class="sitecolor">{{$value['services']}}</span><br/>
                                  </td></tr>
                                </table>
                                </div>
                              </td>
                              <td>
                                <div class="pull-left">
                                  <table style="">
                                  <tr><td>
                                @if(!empty($value->plan))

                                  @if($main_price != $value['price'])
                                 
                                 <?php echo "1 year"; 
                                    $domain_year = $value['cycle'];
                                 ?>

                                    
                                  @else
                                    {{ $value['cycle'] }} <?php if($value['cycle'] == 1) echo "year"; else echo "years"; ?> <br/>
                                  <?php $domain_year = ''; ?>
                                  @endif
                                  <?php for($t=1; $t <= $total_break ; $t++) { 
                                      echo '&nbsp;<br/>'; 
                                  } ?>
                                @else
                                  <?php $domain_year = $value['cycle']; ?>
                                @endif
                              </td></tr>
                                <?php if(isset($value['addons']) && $value['addons'] != "" && $value['addons'] != null){ ?>
                                 <tr><td>
                                        <?php for ($dad=1; $dad <= $d_ad; $dad++) { 
                                            echo '<br/>';
                                         }
                                       ?>
                                     </td></tr>
                                 <?php } ?>
                                 @if(!empty($domain_year))
                                 <tr><td>
                                  <?php echo $domain_year. ' Years'; ?>
                                  </td></tr>
                                  @endif
                                </table>
                                </div>
                              </td>
                              <td>
                                <table style="">
                                <tr><td>
                                @if(!empty($value->plan))
                                @if($main_price != $value['price']) 
                                  {{$value['qty']}} 
                                  <?php $domain_qty = $value['qty']; ?>
                                @else
                                  {{$value['qty']}}
                                  <?php $domain_qty = ''; ?>
                                @endif
                                <?php for($t=1; $t <= $total_break ; $t++) { 
                                                  echo '<br/>'; 
                                              } ?>
                                @else
                                <?php $domain_qty = $value['qty']; ?>
                                @endif
                              </td></tr>
                                <?php 
                                  if(isset($value['addons']) && $value['addons'] != "" && $value['addons'] != null){
                                    $addons_vl = explode(',', $value['addons']); ?>
                                    <tr><td>
                                    <br>
                                    <ul style="margin-bottom:0"> 
                                    @foreach($addons_vl as $addon)
                                      @foreach($domain_pricings as $dprice)
                                      
                                        <?php if($addon == $dprice->id){ ?>
                                          <li>1</li>
                                        <?php } ?>
                                      @endforeach
                                    @endforeach
                                    </ul>
                                  </td></tr>

                                    <?php } ?>
                                    
                                 <!-- </div> -->
                                 <?php if(!empty($domain_qty)){ ?>
                                 <tr><td>

                                             {{ $domain_qty }}
                                             </td></tr> 
                                           <?php } ?>
                                         </table>
                              </td>
                              <td>
                                <table style="">

                                @if(!empty($value->plan))
                                <tr><td>
                                  <?php 
                                    if($main_price != $value['price']){ ?>
                                      RM {{ $main_price }} 
                                      <?php $d_price = $domain_price; ?>
                                     
                                   <?php }else{ ?>
                                      RM {{ number_format(($value['price']?$value['price']:'0'),2)}} <br>
                                      <?php $d_price = ''; ?>
                                   <?php } ?>
                                   <?php for($d=1; $d <= $total_break ; $d++) { 
                                        echo '<br/>'; 
                                    } ?>
                                  @else
                                    <?php $d_price =  number_format(($value['price']?$value['price']:'0'),2); ?> 
                                 </td></tr>
                                  @endif
                                
                                <!-- <div>   -->
                                  <?php
                                  if(isset($value['addons']) && $value['addons'] != "" && $value['addons'] != null){
                                    $addons_vl = explode(',', $value['addons']); ?>
                                    <tr><td>
                                    <br>
                                    <ul style="margin-bottom:0"> 
                                    @foreach($addons_vl as $addon)
                                      @foreach($domain_pricings as $dprice)
                                        <?php if($addon == $dprice->id){ ?>
                                          <li>RM {{ number_format($dprice->price, 2)}}</li>
                                        <?php } ?>
                                      @endforeach
                                    @endforeach
                                    </ul>
                                    </td></tr>
                                  <?php } ?>
                                 <!-- </div> -->
                                 @if($value['type'] == 2)
                                  <tr><td><a href="{{ route('price_list',$value['services']) }}" target="_blank">RM {{ number_format(($value['price']?$value['price']:'0'),2)}}</a> <br> </td></tr>
                                @else
                                  @if(!empty($d_price))
                                  <tr><td>RM {{ $d_price }} </td></tr>
                                  @endif
                                @endif
                              </table>
                                <!-- RM 684.00 -->
                              </td>
                            </tr>

                            <?php $total_price += $row_price; 
                              $discount_price += $discount; 
                            ?>
                          @endforeach    
                          <?php
                          $grand_total = $total_price - $discount_price;  
                          ?>                    
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="7"></td>
                          </tr>
                        </tfoot>
                      </table>
                      <div class="clearfix"></div>         
                </div>
                <!-- end table responsive -->
                
                <div class="one_half_less">
                  <a href="{{url('order_history_list')}}" class="btn btn-default caps pull-left"><i class="fa icon-action-undo"></i> <b>Back</b></a>
                </div><!-- one half less -->

                
                <div class="one_half_less last">
                   <div class="alertymes7">
                             <div class="pull-left caps"><b>Subtotal</b></div>
                             <div class="pull-right"><b>RM {{number_format($total_price, 2)}}</b></div>
                             <div class="clearfix"></div>
                             <div class="pull-left caps red"><b>Discount</b></div>
                             <div class="pull-right red"><b>- RM {{number_format($discount_price, 2)}}</b></div>
                             <div class="clearfix"></div>
                             <div class="pull-left caps"><b>GST (6%)</b></div>
                             <div class="pull-right"><b>RM 0.00</b></div>
                             <div class="divider_line"></div>
                             <div class="clearfix margin_bottom2"></div>
                             <h2 class="red aliright" style="margin-bottom: 0px;"><b>RM {{number_format($grand_total, 2)}}</b></h2><span class="pull-right red caps aliright">Total</span>
                             <div class="clearfix margin_bottom2"></div>
                             <a href="{{url('downloadReceipt'.'/'.$orderDetails->id)}}" class="btn btn-primary caps pull-left" target="_blank">Download Receipt</a>
                             
                   </div> 
                </div><!-- end one half less last --> 
       
       
        </div><!-- end section -->
       
       
       </div><!-- end section -->
        
        

        
    </div>  
    <!-- end container -->  
    
    
    <div class="clearfix"></div>
    
    
</div><!-- end content full width -->

<div class="clearfix"></div>
@endsection