<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Order Confirmation - Order #{{ $mailData['order']->id }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 1.6; color: #333333;">
    <!-- Wrapper Table -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main Container -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #007bff; padding: 30px 40px; text-align: center;">
                            @if(isset($mailData['userType']) && $mailData['userType'] == 'customer')
                                <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Thank You For Your Order!</h1>
                                <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 16px;">We've received your order and will process it shortly.</p>
                            @else
                                <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">New Order Received!</h1>
                                <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 16px;">You have received a new order that requires your attention.</p>
                            @endif
                        </td>
                    </tr>

                    <!-- Order Information Section -->
                    <tr>
                        <td style="padding: 30px 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px; font-weight: bold; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Order Information</h2>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #666666; font-size: 14px;">Order ID:</strong>
                                        <span style="color: #333333; font-size: 16px; font-weight: bold; margin-left: 10px;">#{{ $mailData['order']->id }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #666666; font-size: 14px;">Order Date:</strong>
                                        <span style="color: #333333; font-size: 16px; margin-left: 10px;">{{ \Carbon\Carbon::parse($mailData['order']->created_at)->format('d M, Y') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #666666; font-size: 14px;">Order Status:</strong>
                                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 14px; font-weight: bold; margin-left: 10px;
                                            @if($mailData['order']->status == 'pending')
                                                background-color: #ffc107; color: #333333;
                                            @elseif($mailData['order']->status == 'shipped')
                                                background-color: #17a2b8; color: #ffffff;
                                            @elseif($mailData['order']->status == 'delivered')
                                                background-color: #28a745; color: #ffffff;
                                            @elseif($mailData['order']->status == 'cancelled')
                                                background-color: #dc3545; color: #ffffff;
                                            @else
                                                background-color: #6c757d; color: #ffffff;
                                            @endif
                                        ">
                                            {{ ucfirst($mailData['order']->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">
                                        <strong style="color: #666666; font-size: 14px;">Payment Status:</strong>
                                        <span style="display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 14px; font-weight: bold; margin-left: 10px;
                                            @if($mailData['order']->payment_status == 'paid')
                                                background-color: #28a745; color: #ffffff;
                                            @else
                                                background-color: #dc3545; color: #ffffff;
                                            @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $mailData['order']->payment_status)) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Shipping Address Section -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px; font-weight: bold; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Shipping Address</h2>
                            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 4px; border-left: 4px solid #007bff;">
                                <p style="margin: 0 0 8px 0; color: #333333; font-size: 16px; font-weight: bold;">
                                    {{ $mailData['order']->first_name }} {{ $mailData['order']->last_name }}
                                </p>
                                <p style="margin: 0 0 4px 0; color: #666666; font-size: 14px; line-height: 1.8;">
                                    {{ $mailData['order']->address }}<br>
                                    @if(!empty($mailData['order']->apartment))
                                        {{ $mailData['order']->apartment }},<br>
                                    @endif
                                    {{ $mailData['order']->city }}, {{ $mailData['order']->state }} {{ $mailData['order']->zip }}<br>
                                    @php
                                        $countryInfo = getCountryInfo($mailData['order']->country_id);
                                    @endphp
                                    @if(!empty($countryInfo))
                                        <strong>Country:</strong> {{ $countryInfo->name }}<br>
                                    @endif
                                    <strong>Phone:</strong> {{ $mailData['order']->mobile }}<br>
                                    <strong>Email:</strong> {{ $mailData['order']->email }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Order Items Section -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px; font-weight: bold; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Order Items</h2>
                            
                            @if(isset($mailData['order']->items) && $mailData['order']->items->isNotEmpty())
                                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-collapse: collapse; border: 1px solid #dee2e6;">
                                    <thead>
                                        <tr style="background-color: #f8f9fa;">
                                            <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; color: #333333; font-size: 14px; font-weight: bold;">Product</th>
                                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #dee2e6; color: #333333; font-size: 14px; font-weight: bold;">Price</th>
                                            <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; color: #333333; font-size: 14px; font-weight: bold;">Qty</th>
                                            <th style="padding: 12px; text-align: right; border-bottom: 2px solid #dee2e6; color: #333333; font-size: 14px; font-weight: bold;">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mailData['order']->items as $item)
                                            <tr>
                                                <td style="padding: 12px; border-bottom: 1px solid #dee2e6; color: #333333; font-size: 14px;">{{ $item->name }}</td>
                                                <td style="padding: 12px; border-bottom: 1px solid #dee2e6; text-align: right; color: #333333; font-size: 14px;">${{ number_format($item->price, 2) }}</td>
                                                <td style="padding: 12px; border-bottom: 1px solid #dee2e6; text-align: center; color: #333333; font-size: 14px;">{{ $item->qty }}</td>
                                                <td style="padding: 12px; border-bottom: 1px solid #dee2e6; text-align: right; color: #333333; font-size: 14px; font-weight: bold;">${{ number_format($item->total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p style="margin: 0; color: #666666; font-size: 14px; padding: 20px; background-color: #f8f9fa; border-radius: 4px; text-align: center;">No order items found.</p>
                            @endif
                        </td>
                    </tr>

                    <!-- Order Summary Section -->
                    <tr>
                        <td style="padding: 0 40px 30px 40px;">
                            <h2 style="margin: 0 0 20px 0; color: #333333; font-size: 22px; font-weight: bold; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Order Summary</h2>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="padding: 10px 0; color: #666666; font-size: 14px;">Subtotal:</td>
                                    <td style="padding: 10px 0; text-align: right; color: #333333; font-size: 14px;">${{ number_format($mailData['order']->subtotal, 2) }}</td>
                                </tr>
                                @if($mailData['order']->discount > 0)
                                <tr>
                                    <td style="padding: 10px 0; color: #666666; font-size: 14px;">
                                        Discount{{ (!empty($mailData['order']->promo_code)) ? ' (' . $mailData['order']->promo_code . ')' : '' }}:
                                    </td>
                                    <td style="padding: 10px 0; text-align: right; color: #28a745; font-size: 14px; font-weight: bold;">-${{ number_format($mailData['order']->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 10px 0; color: #666666; font-size: 14px;">Shipping:</td>
                                    <td style="padding: 10px 0; text-align: right; color: #333333; font-size: 14px;">${{ number_format($mailData['order']->shipping, 2) }}</td>
                                </tr>
                                <tr style="border-top: 2px solid #dee2e6;">
                                    <td style="padding: 15px 0 10px 0; color: #333333; font-size: 18px; font-weight: bold;">Grand Total:</td>
                                    <td style="padding: 15px 0 10px 0; text-align: right; color: #007bff; font-size: 20px; font-weight: bold;">${{ number_format($mailData['order']->grand_total, 2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px 40px; text-align: center; border-top: 1px solid #dee2e6;">
                            @if(isset($mailData['userType']) && $mailData['userType'] == 'customer')
                                <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                    Thank you for shopping with us!<br>
                                    If you have any questions about your order, please don't hesitate to contact our support team.
                                </p>
                            @else
                                <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                    Please review this order and process it accordingly.<br>
                                    This order requires your attention in the admin panel.
                                </p>
                            @endif
                            <p style="margin: 20px 0 0 0; color: #999999; font-size: 12px;">
                                This is an automated email. Please do not reply to this message.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
