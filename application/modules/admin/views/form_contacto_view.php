<div id="contac_form">
    <form name="formcontac" id="formcontac">
    <table border="0" cellpadding="0" cellspacing="0" width="750">
        <tbody><tr>
            <td width="300">Nombre completo:</td>
            <td width="350"><input type="text" name="nombre" id="nombre" value=""></td>
            <td width="300">Área de servicio:</td>
            <td width="350"><?=$this->repetidos_model->areaServicios();?></td>
        </tr>
        <tr>
            <td>Organización:</td>
            <td><input type="text" name="organizacion" id="organizacion" value=""></td>
            <td valign="top">Mensaje:</td>
            <td rowspan="4" valign="top"><textarea id="mensaje" name="mensaje" rows="7" cols="30" ></textarea></td>
        </tr>
        <tr>
            <td>Ciudad:</td>
            <td><input type="text" name="ciudad" id="ciudad" value=""></td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>País:</td>
            <td><input type="text" name="pais" id="pais" value=""></td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>E-mail:</td>
            <td><input type="text" name="email" id="email" value=""></td>
            <td>&nbsp;</td>
          </tr>
        <tr>
            <td>Teléfono:</td>
            <td><input type="text" name="telefono" id="telefono" value=""></td>
            <td>&nbsp;</td>
            <td><input type="submit" value="Enviar" id="enviar" name="enviar"></td>
        </tr>
    </tbody></table>
    </form>
</div>