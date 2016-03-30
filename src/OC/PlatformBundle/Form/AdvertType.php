<?php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
      //->add('date',      'date')
      ->add('title',     'text')
      ->add('author',    'text')
      ->add('content',   'textarea')
      ->add('imageheader',      new ImageHeaderType() )
      ->remove('date')
      ->remove('published')
      ->add('imagecontents', 'collection', array(
        'type'         => new ImageContentType(),
        'allow_add'    => true,
        'allow_delete' => true
      ))

      ->add('save',      'submit');
        // On ajoute une fonction qui va écouter un évènement
      $builder->addEventListener(
        FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
        function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
          // On récupère notre objet Advert sous-jacent
          $advert = $event->getData();

          // Cette condition est importante, on en reparle plus loin
          if (null === $advert) {
            return; // On sort de la fonction sans rien faire lorsque $advert vaut null
          }

          if (!$advert->getPublished() || null === $advert->getId()) {
            // Si l'annonce n'est pas publiée, ou si elle n'existe pas encore en base (id est null),
            // alors on ajoute le champ published
            $event->getForm()->add('published', 'checkbox', array('required' => false));
          } else {
            // Sinon, on le supprime
            $event->getForm()->remove('published');
          }
        }
    );
  }
    



    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Advert'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oc_platformbundle_advert';
    }
}
