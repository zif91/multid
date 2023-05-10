<form action="post" class="cart-form" data-commerce-order="[+form_hash+]">

	<h2 class="cart-form-title">
		Оформление заказа
	</h2>

	<div>
		<label>Имя</label>
		<input type="text" name="name" value="[+name.value+]" placeholder="Имя">
	</div>
	<br/>


	<div>
		<label>Телефон</label>
		<input type="tel" name="phone" value="[+phone.value+]"  placeholder="+7 (" >
	</div>
	<br/>

	<div>
		<label>Email</label>
		<input type="email" name="email" value="[+email.value+]" placeholder="E-mail">
	</div>
	<br/>

	<div>
		<label>Адрес доставки</label>
		<input type="text" name="address" value="[+address.value+]" placeholder="Ваш адрес" >
	</div>
	<br/>

	<div>
		<label>Комментарий</label>
		<textarea placeholder="Комментарий" name="comment">[+comment.value+]</textarea>
	</div>
	<br/>

	<div data-commerce-deliveries>
		[+delivery+]
	</div>
	<br/>

	<div data-commerce-payments>
		[+payments+]
	</div>
	<br/>

	<button type="submit">Оформить заказ</button>
				
</form>