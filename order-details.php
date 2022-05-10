<?php 
$total_price = 0;
$discount_price = 0.00;
?>
@section('content')



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
                            @if(!empty($value->plan) )
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
                              @if(!empty($value->plan) && $value->plan_id != 0)
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
                              <tr>

                                  @else
                                  <?php 
                                  $domain_year = $value['cycle'];
                                  $domain_qty = $value['qty'];
                                  $d_price =  number_format(($value['price']?$value['price']:'0'),2);
                                  
                                   ?>
                                 
                                   
                              @endif
                             
                              <?php if(isset($value->addons) && $value->addons != "" && $value->addons != null){ ?>
                                  
                                  <td>
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
                            <tr>
                              <?php } ?>
                              

                            
                              
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
       
       
        
@endsection