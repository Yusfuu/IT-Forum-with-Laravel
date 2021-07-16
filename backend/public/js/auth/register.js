const form = document.querySelector("#form");
const endpoint = form.getAttribute('action');

form.onsubmit = async (e) => {
  e.preventDefault();
  const fd = new FormData(form);

  const config = {
    method: 'POST',
    headers: {
      'Accept': 'application/json'
    },
    body: fd,
  };

  const response = await fetch(endpoint, config);
  const result = await response.json();
  console.log(result);

};
