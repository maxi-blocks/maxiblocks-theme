document.addEventListener('DOMContentLoaded', function() {
// Array of container classes to check
const containers = [
    'container-maxi-2b52d1cc-u',
    'container-maxi-4dcd096b-u',
    'container-maxi-823f8de3-u',
    'container-maxi-1443e495-u',
    'container-maxi-d5da4162-u'
  ];

  // Get all the sections in the document
  const sections = document.getElementsByTagName('section');

  // Loop through each section
  for (let i = 0; i < sections.length; i++) {
    const section = sections[i];

    // Check if the section has any of the desired classes
    if (containers.some(container => section.classList.contains(container))) {
        // Find all child sections with the class 'maxi-container-block'
        const childSections = section.querySelectorAll('section.maxi-container-block');
  
        // Move each child section out of the parent section in reverse order
        for (let j = childSections.length - 1; j >= 0; j--) {
          const childSection = childSections[j];
          section.parentNode.insertBefore(childSection, section.nextSibling);
        }
      }
  }
});
