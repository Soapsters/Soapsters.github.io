url = 'https://pokeapi.co/api/v2/pokemon/';

document.getElementById('enterButton').onclick = async function() {
  let val = document.getElementById('pokemonName').value;
  console.log(val);
let response = await fetch(url + val);
console.log(response.status);
let json = await response.json();

const dataTypes = json.types;
const dataFirstType = dataTypes[0];
const dataSecondType = dataTypes[1];
const pokeTypeOne = document.getElementById('typeOne');
const pokeTypeTwo = document.getElementById('typeTwo');
pokeTypeOne.innerHTML = dataFirstType.type.name;
if(dataSecondType) {
    pokeTypeTwo.innerHTML = dataSecondType.type.name;
} else {
    pokeTypeTwo.innerHTML = '';
}

document.getElementById('response').innerHTML = '<b>' + json.name + 
'</b><br><img src="' + json.sprites.front_default + '" />' 
+ '</b><br><img src="' + json.sprites.back_default + '" />'
+ '<br><h3> Type: ' + pokeTypeOne.innerHTML + '</h3></b>' 
+ '<h3> Type 2: ' + pokeTypeTwo.innerHTML + '</h3></b>'
+ '<h3> Height: ' + json.height + ' dm </h3>' + '<h3> Weight: ' + json.weight + ' hg </h3>';

}