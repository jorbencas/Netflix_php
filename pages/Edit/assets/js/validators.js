'use strict';
function validateAnime(data) {
  let valid = true;
  if (data['siglas'] === '' || data['siglas'] === null || data['siglas'] === undefined) {
      message = "Debes de introducir la siglas para poder añadir un anime";
      valid = false;
    } else if (data['titulo_es'] === '' || data['titulo_es'] === null || data['titulo_es'] === undefined) {
      message = "Debes de introducir el titulo en español para poder añadir un anime";
      valid = false;
    } else if (data['sinopsis_es'] === '' || data['sinopsis_es'] === null || data['sinopsis_es'] === undefined) {
      message = "Debes de introducir la sinopsis en español para poder añadir un anime";
      valid = false;
    } else {
      let media = $(geteditnode() + " tr img");
      if (media.length == 0) {
        message = "Debes de introducir algun fichero multimedia añadir un anime";
        valid = false;
      } else if (media.length > 3) {
        message = "Debes de introducir solamente 2 ficheros uno de tipo banner y otro de tipo portada fichro multimedia añadir un anime";
        valid = false;
      } else {
        let first = $(geteditnode() + " td:first-child img").attr("type");
        let second = $(geteditnode() + " tr:nth-child(2) img").attr("type");

        if (first !== 'banner' && second !== 'banner') {
          message = "Debes de introducir una imagen de banner";
          valid = false;
        } else if (first !== 'portada' && second !== 'portada') {
          message = "Debes de introducir una imagen de portada";
          valid = false;
        }
      }
    }
  return { "valid": valid, "message": message };
}

function validateEndings(data) {
  let valid = true;
  if (data['nombre'] === '' || data['nombre'] === null || data['nombre'] === undefined) {
    message = "Debes de introducir todos los datos para poder añadir un endings";
    valid = false;
  } else if (data['descripcion'] === '' || data['descripcion'] === null || data['descripcion'] === undefined) {
    message = "Debes de introducir la descripción para poder añadir un endings";
    valid = false;
  } else {
    let media = $(geteditnode() + " tr:first-child img");
    if (media.length === 0) {
      message = "Debes de introducir el fichero del ending";
      valid = false;
    } else if (media.length > 1) {
      message = "Debes de introducir solo un fichero del ending";
      valid = false;
    }
  }
  return { "valid": valid, "message": message };
}

function validatePersonage(data) {
  let valid = true;
  if (data['nombre'] === '' || data['nombre'] === null || data['nombre'] === undefined) {
    message = "Debes de introducir todos los datos para poder añadir un personage";
    valid = false;
  } else if (data['descripcion'] === '' || data['descripcion'] === null || data['descripcion'] === undefined) {
    message = "Debes de introducir la descripción para poder añadir un personage";
    valid = false;
  } else {
    let media = $(geteditnode() + " tr:first-child img");
    if (!media) {
      message = "Debes de introducir la imagen del personage";
      valid = false;
    }
  }
  return { "valid": valid, "message": message };
}

function validateOpenings(data) {
  let valid = true;
  if (data['nombre'] === '' || data['nombre'] === null || data['nombre'] === undefined) {
    message = "Debes de introducir todos los datos para poder añadir un opening";
    valid = false;
  } else if (data['descripcion'] === '' || data['descripcion'] === null || data['descripcion'] === undefined) {
    message = "Debes de introducir la descripción para poder añadir un opening";
    valid = false;
  } else {
    let media = $(geteditnode() + " tr:first-child img");
    if (media.length === 0) {
      message = "Debes de introducir el fichero del opening";
      valid = false;
    } else if (media.length > 1) {
      message = "Debes de introducir solo un fichero del opening";
      valid = false;
    }
  }
  return { "valid": valid, "message": message };
}

function validateEpisodes(data) {
  let valid = true;
  if (data['sinopsis_es'] === '' || data['sinopsis_es'] === null || data['sinopsis_es'] === undefined) {
    message = "Debes de introducir la sinopsis en español para poder añadir un episodio";
    valid = false;
  } else if (data['titulo_es'] === '' || data['titulo_es'] === null || data['titulo_es'] === undefined) {
    message = "Debes de introducir el titulo en español para poder añadir un episodio";
    valid = false;
  } else {
    let media = $(geteditnode() + " tr img");
    if (media.length === 0) {
      message = "Debes de introducir el fichero del episodio";
      valid = false;
    } else if (media.length > 1) {
      message = "Debes de introducir solo un fichero del episodio";
      valid = false;
    }
  }
  return { "valid": valid, "message": message };
}