url = 'https://pokeapi.co/api/v2/pokemon/';

document.getElementById('enterButton').onclick = async function() {
  let val = document.getElementById('pokemonName').value;
  console.log(val);
let response = await fetch(url + val);
console.log(response.status);

let json = await response.json();
console.log(json);
document.getElementById('response').innerHTML = '<b>' + json.name + 
'</b><br/><img src="' + json.sprites.front_default + '" />';
}