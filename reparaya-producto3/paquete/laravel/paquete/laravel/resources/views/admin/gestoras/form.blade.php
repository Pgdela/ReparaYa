<div>
    <h3>Datos de la empresa</h3>

    <label>Nombre de la empresa *</label><br>
    <input type="text" name="nombre" value="{{ old('nombre', $gestora->nombre ?? '') }}" required maxlength="255">
    @error('nombre') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>CIF</label><br>
    <input type="text" name="cif" value="{{ old('cif', $gestora->cif ?? '') }}" maxlength="30">
    @error('cif') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Correo electrónico de contacto</label><br>
    <input type="email" name="email" value="{{ old('email', $gestora->email ?? '') }}">
    @error('email') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Teléfono</label><br>
    <input type="text" name="telefono" value="{{ old('telefono', $gestora->telefono ?? '') }}">
    @error('telefono') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Dirección</label><br>
    <input type="text" name="direccion" value="{{ old('direccion', $gestora->direccion ?? '') }}">
    @error('direccion') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Porcentaje de comisión *</label><br>
    <input type="number" name="porcentaje_comision" min="0" max="100" step="0.01"
           value="{{ old('porcentaje_comision', $gestora->porcentaje_comision ?? '0.00') }}" required>
    @error('porcentaje_comision') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Estado *</label><br>
    <select name="activa" required>
        <option value="1" {{ (string)old('activa', isset($gestora) ? (int)$gestora->activa : 1) === '1' ? 'selected' : '' }}>Activa</option>
        <option value="0" {{ (string)old('activa', isset($gestora) ? (int)$gestora->activa : 1) === '0' ? 'selected' : '' }}>Inactiva</option>
    </select>
    @error('activa') <p style="color:red">{{ $message }}</p> @enderror
</div>

<hr>
<h3>Cuenta de acceso de la gestora</h3>

<div>
    <label>Correo electrónico para iniciar sesión *</label><br>
    <input type="email" name="usuario_email"
           value="{{ old('usuario_email', isset($gestora) ? ($gestora->usuario?->email ?? '') : '') }}"
           required maxlength="255">
    @error('usuario_email') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Contraseña {{ isset($gestora) && $gestora->usuario ? '(dejar en blanco para mantener la actual)' : '*' }}</label><br>
    <input type="password" name="usuario_password"
           {{ isset($gestora) && $gestora->usuario ? '' : 'required' }}
           minlength="8" autocomplete="new-password">
    @error('usuario_password') <p style="color:red">{{ $message }}</p> @enderror
</div>

<div>
    <label>Repetir contraseña</label><br>
    <input type="password" name="usuario_password_confirmation"
           {{ isset($gestora) && $gestora->usuario ? '' : 'required' }}
           minlength="8" autocomplete="new-password">
</div>