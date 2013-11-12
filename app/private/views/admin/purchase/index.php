<?php $this->menu();  ?>

<h1 class="grid_12"><span>Buy New Domain Access</span></h1>

<div class="grid_12">
    <div class="grid_6">
        <form id="form_payment" action='/admin/purchase/buy' method='post' class="full">
            <input type='hidden' name='li_0_type' value='key' />
            <input type='hidden' name='li_0_name' value='New Key Purchase' />
            <input type='hidden' name='li_0_price' value='5.00' />
            <input type='hidden' name='li_0_recurrence' value='1 Year' />
            <h3>Secure Payment Info</h3>
            <ul id="payment">
                <li><img src="/theme/img/payment-icon-set/mastercard-curved-32px.png" alt="Mastercard"/></li>
                <li><img src="/theme/img/payment-icon-set/visa-curved-32px.png" alt="Visa"/></li>
                <li><img src="/theme/img/payment-icon-set/american-express-curved-32px.png" alt="Amex"/></li>
                <li><img src="/theme/img/payment-icon-set/discover-curved-32px.png" alt="Discover"/></li>
            </ul>
            <div class="row freeLabel">
                <label for="name">Name (as it appears on your card)</label>
                <input type="text" name="name" class="grid_7"/>
            </div>
            <div class="row freeLabel">
                <label for="number">Card Number (no dashes or spaces)</label>
                <input type="text" name="number" class="grid_7"/>
            </div>
            <div class="row freeLabel">
                <label for="number">Expiration Date</label>
                <select name="" id="" class="grid_4">
                    <option value="01">01 - January</option>
                </select>
                <select name="" id="" class="grid_3">
                    <option value="2013">2013</option>
                </select>
            </div>
            <div class="row freeLabel">
                <label for="number">Security code (3 on back, Amex: 4 on front)</label>
                <input type="text" name="number" class="grid_3" style="margin-right: 5px;"/>
                <img src="/theme/img/payment-icon-set/seccode.gif" alt="Security Code"/>
                <img src="/theme/img/payment-icon-set/amexseccode.png" alt="Security Code"/>
            </div>
            <div class="row" style="margin-left: 3px; margin-top: 10px;">
                <button>Checkout</button>
            </div>
        </form>
    </div>    
</div>