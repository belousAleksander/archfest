<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Admin\Entity;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

class UserAdmin extends BaseUserAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('groups')
            ->add('enabled', null, array('editable' => true))
            ->add('locked', null, array('editable' => true))
            ->add('createdAt')
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'text', array(
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
            ));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->end()
//            ->with('Groups')
//            ->add('groups')
//            ->end()
//            ->with('Profile')
//            ->add('dateOfBirth')
//            ->add('firstname')
//            ->add('lastname')
//            ->add('website')
//            ->add('biography')
//            ->add('gender')
//            ->add('locale')
//            ->add('timezone')
//            ->add('phone')
//            ->end()
//            ->with('Social')
//            ->add('facebookUid')
//            ->add('facebookName')
//            ->add('twitterUid')
//            ->add('twitterName')
//            ->add('gplusUid')
//            ->add('gplusName')
//            ->end()
//            ->with('Security')
//            ->add('token')
//            ->add('twoStepVerificationCode')
//            ->end()
        ;
    }
}
