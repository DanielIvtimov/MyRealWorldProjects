from django.db import models
from django.utils.text import slugify
from django.utils.crypto import get_random_string

# Create your models here.
class Note(models.Model):

    # A tuple of choices for the "category" field. The first value is what gets stored in the database. The second is value of human-readable name shown in the admin.
    CATEGORY = (('BUSINESS', 'Business'), ('PERSONAL', 'Personal'), ('IMPORTANT', 'Imporant'))

    title = models.CharField(max_length=100)
    body = models.TextField()
    slug = models.SlugField(unique=True, blank=True, null=True)
    category = models.CharField(max_length=15, choices=CATEGORY, default="PERSONAL")
    created = models.DateTimeField(auto_now_add=True)
    updated = models.DateTimeField(auto_now=True) 

    # This is special python method to defines how the Model object is represented as a string.
    def __str__(self):
        return self.title

    def save(self, *args, **kwargs):
        # if this model does not have already slug ( its new model for saving )
        if not self.slug:
            # convert the title into a URL friendly slug 
            slug_base = slugify(self.title)
            # start with the base slug
            slug = slug_base
            # checking if another model already has this slug
            if Note.objects.filter(slug = slug).exists() :
                # if yes - then append random characters to make it unique 
                slug = f"{slug_base}-{get_random_string(5)}"
            
            # Assign the final slug to this model
            self.slug = slug
        #Call the original Django save() method to actually save the object
        super().save(*args, **kwargs)