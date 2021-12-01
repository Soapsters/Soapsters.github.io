url = 'https://pokeapi.co/api/v2/pokemon/';

document.getElementById('enterButton').onclick = async function() {
  let val = document.getElementById('pokemonName').value;
  console.log(val);
let response = await fetch(url + val);
console.log(response.status);

let json = await response.json();
console.log(json);
document.getElementById('response').innerHTML = '<b>' + json.name + 
'</b><br><img src="' + json.sprites.front_default + '" />' + '<br><h3> Type: ' + json.types[0].type.name + '</h3></b>'
+ '<h3> Height: ' + json.height + ' dm </h3>' + '<h3> Weight: ' + json.weight + ' hg </h3>';
}