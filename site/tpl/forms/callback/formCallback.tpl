<form>
	<h2 class="form-modal-title mb-3">Заказать звонок</h2>
	
		
	<div class="form-group">
		<label class="form-label">Имя</label>
		<input type="text" class="form-control" name="name" value="[+name.value+]" placeholder="Имя">
	</div>

	<div class="form-group">
		<label class="form-label">Телефон</label>
		<input type="tel" class="form-control phone-mask" name="phone" value="[+phone.value+]" placeholder="Телефон">
	</div>

	<div class="form-group">
		<label class="form-label">E-mail</label>
		<input type="text" class="form-control" name="email" value="[+email.value+]" placeholder="E-mail">
	</div>

	
	<button type="submit" class="btn btn-warning w-100">
		Отправить
	</button>
	
	
	[+form.messages+]
</form>