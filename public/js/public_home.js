/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('span.target').parent().on('click',function(){
    $(this).find('div.target').slideToggle();
})