<?php

namespace Xlucaspx\Dojotech\Api\Repository;

use Doctrine\ORM\EntityRepository;
use Xlucaspx\Dojotech\Api\Entity\User\NewUserDto;
use Xlucaspx\Dojotech\Api\Entity\User\UpdateUserDto;
use Xlucaspx\Dojotech\Api\Entity\User\User;
use Xlucaspx\Dojotech\Api\Exception\DuplicateKeyException;

class UserRepository extends EntityRepository
{
	/** @return int Returns the generated ID */
	public function add(NewUserDto $userData): int
	{
		$user = new User($userData);

		$em = $this->getEntityManager();
		$em->persist($user);
		$em->flush();

		return $user->id();
	}

	public function update(UpdateUserDto $userData): void
	{
		/** @var User $user */
		$user = $this->find($userData->id);

		if ($userData->email->email !== $user->email() && $this->existsByEmail($userData->email)) {
			throw new DuplicateKeyException("O E-mail $userData->email não está disponível!");
		}

		if ($userData->username->username !== $user->username() && $this->existsByUsername($userData->username)) {
			throw new DuplicateKeyException("O nome de usuário $userData->username não está disponível!");
		}

		$user->update($userData);

		$this->getEntityManager()->flush();
	}

	public function delete(int $id): void
	{
		/** @var User $user */
		$user = $this->find($id);

		$em = $this->getEntityManager();
		$em->remove($user);
		$em->flush();
	}

	public function existsByEmail(string $email): bool
	{
		$qb = $this->createQueryBuilder('user');

		$qb->select('COUNT(user.id)')
			->where('user.email = :email')
			->setParameter('email', $email);

		$count = $qb->getQuery()->getSingleScalarResult();

		return $count > 0;
	}

	public function existsByUsername(string $username): bool
	{
		$qb = $this->createQueryBuilder('user');

		$qb->select('COUNT(user.id)')
			->where('user.username = :username')
			->setParameter('username', $username);

		$count = $qb->getQuery()->getSingleScalarResult();

		return $count > 0;
	}
}
