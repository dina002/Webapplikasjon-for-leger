/* kalender */

window.onload=init;

function init()
{
  datePickerController.createDatePicker
  (
    {
      formElements:
        {
          "dato":"%Y-%m-%d"                         
        }
    }
  );
  
}